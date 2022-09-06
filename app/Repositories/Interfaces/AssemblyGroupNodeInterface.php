<?php
namespace App\Repositories\Interfaces;

interface AssemblyGroupNodeInterface{

    public function index(); // for AssemblyGroupNodes listing

    public function show($id); // for AssemblyGroupNodes showing

    public function getSectionParts($id); // for AssemblyGroupNodes showing

    public function languages($id); // for AssemblyGroupNodes showing
    

}