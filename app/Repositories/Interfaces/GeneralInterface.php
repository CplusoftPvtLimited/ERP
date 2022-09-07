<?php
namespace App\Repositories\Interfaces;

interface GeneralInterface{

    public function getManufacturer(); // for manufacturer listing
    public function getModel($id); // for Models against a specific Manufacturer
    

}