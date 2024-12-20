<?php
namespace BestLoc\Controller;

use BestLocLib\Service\BillingService;

class BillingController {
    private BillingService $service;

    public function __construct() {
        $this->service = new BillingService();
    }
    
    public function getAll() {
        
    }
    
    public function find() {
        
    }
    
    public function getByContract() {
        
    }
    
    public function create() {
        
    }
    
    public function update() {
        
    }
    
    public function delete() {
        
    }
}