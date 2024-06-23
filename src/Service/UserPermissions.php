<?php
namespace App\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserPermissions {
	
	private $token_storage;

    public function __construct(TokenStorageInterface $tokenStorage) {
        $this->token_storage =  $tokenStorage;
    }
    public function isSuperAdmin(){
        return $this->userIs('ROLE_SUPER_ADMIN');
    }
   	public function isAdmin(){
    	return $this->userIs('ROLE_ADMIN');
    }

    public function isTeacher(){
    	return $this->userIs('ROLE_TEACHER');
    }

    public function isClient(){
        return $this->userIs('ROLE_CLIENT');
    }

    public function isStudent(){
    	return $this->userIs('ROLE_STUDENT');
    }

    public function isLibrarian(){
        return $this->userIs('ROLE_LIBRARIAN');
    }

    private function userIs($role){
        // if ($this->token_storage->getToken() == null){
        //     return true;
        // }

        if($this->token_storage->getToken() != null){
            return in_array($role, $this->token_storage->getToken()->getUser()->getRoles())?true:false;
        }else {
            return false;
        }
    }

     /** 
     * @param (array) $roles = [admin, Teacher, Librarian, student, client]
     * @return bool
     */
    public function accessLevel($roles = []) {
        $access = false;
        foreach ($roles as $role) {
            $role = 'ROLE_'.strtoupper($role);
            if($this->userIs($role)){
               $access = true; 
            }
        }
        return $access;
    }
}