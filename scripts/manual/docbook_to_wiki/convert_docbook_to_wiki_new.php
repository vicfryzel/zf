﻿<?php

require_once 'functions.php';

$confluenceWsdl  = 'http://framework.zend.com/wiki/rpc/soap-axis/confluenceservice-v1?wsdl';
$confluenceUser  = '';
$confluencePass  = '';
$confluenceSpace = 'ZFDOCDEV';

$dir  = '../../../documentation/manual/en/';
$path = $dir . 'module_specs/';
$file = file_get_contents($dir . 'manual.xml');
$file = preg_replace_callback('/(&module_specs.)(.+)(;)/', 'processChapters', $file);
$sxml = @simplexml_load_string($file);
$soap = new SoapClient($confluenceWsdl);

$token = $soap->login($confluenceUser, $confluencePass);

$chapters = array();
foreach ($sxml->chapter as $key => $chapter) {
    array_push($chapters, $chapter);
}

$homePage = $soap->getPage($token, $confluenceSpace, 'Home');

foreach ($chapters as $key => $chapter)
{
    $filename = $chapter['id'];
    
    echo $chapter->title . "\n";
    
    $mytmp = $chapter->sect1[0]->asXML();
    $mytmp = cleanup($mytmp);
    
    $xml = new DOMDocument;
    $xsl = new DOMDocument();
    
    $xml->loadXML($mytmp);
    $xsl->load('wiki.xsl');
    
    $proc = new XSLTProcessor;
    $proc->importStyleSheet($xsl);
    
    $temp = html_entity_decode($proc->transformToXML($xml));
    $temp = preg_replace('/^(\\s*)(\\|\\|[^\\r\\n]+?)(\\s+)(\\|[^\\|]+)/', "\\2||\r\n\\4", $temp);
    $temp = preg_replace('/^(\\s*)\\|([^|]+.+?)(\\s*)$/m', '|\\2|', $temp);
        
    $page = new stdClass;
    $page->id          = false;
    $page->permissions = true;
    $page->parentId    = false;
    $page->current     = true;
    $page->homePage    = false;
    $page->version     = false;
    $page->space       = $confluenceSpace;
    $page->title       = $chapter->title;
    $page->content     = $temp;
    $page->parentId    = $homePage->id;
    
    $soap->storePage($token, $page);
    
    $parentPage = $soap->getPage($token, $confluenceSpace, $chapter->title);
    
    for ($i = 1; $i < count($chapter->sect1); $i++) {
        
        echo $chapter->sect1[$i]->title . "\n";
        
        $name    = $chapter->sect1[$i]['id'];
        $parent  = explode('.', $name);
        
        array_pop($parent);
        
        $parent   = implode('.', $parent);
        $filename = $chapter->sect1[$i]->title;
        
        echo "NEW PAGE WITH PARENT:  $parent \n";
        
        $mytmp = $chapter->sect1[$i]->asXML();
        $mytmp = cleanup($mytmp);
        
        $xml = new DOMDocument;
        $xsl = new DOMDocument();
        
        $xml->loadXML($mytmp);
        $xsl->load('wiki.xsl');
        
        $proc = new XSLTProcessor;
        $proc->importStyleSheet($xsl);
        
        $temp = html_entity_decode($proc->transformToXML($xml));
        $temp = preg_replace('/^(\\s*)(\\|\\|[^\\r\\n]+?)(\\s+)(\\|[^\\|]+)/', "\\2||\r\n\\4", $temp);
        $temp = preg_replace('/^(\\s*)\\|([^|]+.+?)(\\s*)$/m', '|\\2|', $temp);
        
        $title = str_replace('::', ' - ', $chapter->sect1[$i]->title);
        $title = str_replace(':', ' ', $chapter->sect1[$i]->title);
        
        $page = new stdClass;
        $page->id          = false;
        $page->permissions = true;
        $page->parentId    = false;
        $page->current     = true;
        $page->homePage    = false;
        $page->version     = false;
        $page->space       = $confluenceSpace;
        $page->title       = $title;
        $page->content     = $temp;
        $page->parentId    = $parentPage->id;
        
        $soap->storePage($token, $page);
    }
}