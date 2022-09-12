<?php
namespace App\Repositories\Interfaces;

interface PurchaseInterface{

    public function store($request); // for purchase create
    public function view($id); // for purchase product view
    public function edit($id); // for purchase product edit
    public function updatePurchase($request); // for purchase product update
    public function deletePurchaseProduct($purchase_id,$id); // for delete purchase product




    
    

}