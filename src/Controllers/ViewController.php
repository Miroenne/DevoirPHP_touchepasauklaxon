<?php

namespace App\Controllers;

class ViewController{

    public function dashboard(): string {
        return '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8">'
             . '<title>Dashboard</title></head><body><h1>Dashboard OK</h1></body></html>';
    }

}