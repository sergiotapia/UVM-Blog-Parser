<?php

class NewsModel {
    var $image;
    var $fechanoticia;
    var $title;
    var $description;
    var $sourceurl;

    function get_image( ) {
        return $this->image;
    }

    function set_image ($new_image) {
        $this->image = $new_image;
    }

    function get_fechanoticia( ) {
        return $this->fechanoticia;
    }

    function set_fechanoticia ($new_fechanoticia) {
        $this->fechanoticia = $new_fechanoticia;
    }

    function get_title( ) {
        return $this->title;
    }

    function set_title ($new_title) {
        $this->title = $new_title;
    }

    function get_description( ) {
        return $this->description;
    }

    function set_description ($new_description) {
        $this->description = $new_description;
    }

    function get_sourceurl( ) {
        return $this->sourceurl;
    }

    function set_sourceurl ($new_sourceurl) {
        $this->sourceurl = $new_sourceurl;
    }
}