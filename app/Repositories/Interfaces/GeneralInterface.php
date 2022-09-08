<?php
namespace App\Repositories\Interfaces;

interface GeneralInterface{

    public function getManufacturer(); // for manufacturer listing
    public function getModel($id); // for Models against a specific Manufacturer
    public function getEngineDetails($id); // for Engine Details
    public function getSections($id); //for Sections
    public function getSectionParts($id); //for Section Parts

    

}