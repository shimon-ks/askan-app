<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseManager
{
    private $database;

    public function __construct()
    {
        $firebase = (new Factory)
        ->withServiceAccount(__DIR__ . '/askan-760d1-firebase-adminsdk-y7rex-c53adb23fe.json')
        ->withDatabaseUri('https://askan-760d1-default-rtdb.europe-west1.firebasedatabase.app/');


        $this->database = $firebase->createDatabase();
    }

    public function addUserCategory($userId, $category)
    {

        $userRef = $this->database->getReference('users/' . $userId);
        $userData = $userRef->getValue();

    
        if (!isset($userData['interestedCategories'])) {
            $userData['interestedCategories'] = [];
        }
    
        if (!in_array($category, $userData['interestedCategories'])) {
            $userData['interestedCategories'][] = $category;
        }
    
        $userRef->update(['interestedCategories' => $userData['interestedCategories']]);

        $this->addUserExpoTokenToCategory($userId, $category);

        return $userData['interestedCategories'] ;
    }


    public function removeUserCategory($userId, $category)
    {
        $userRef = $this->database->getReference('users/' . $userId);
        $userData = $userRef->getValue();

        if (!isset($userData['interestedCategories'])) {
            return;
        }

        $index = array_search($category, $userData['interestedCategories']);
        if ($index !== false) {
            unset($userData['interestedCategories'][$index]);
        }

        $userRef->update(['interestedCategories' => array_values($userData['interestedCategories'])]);
        $this->removeUserExpoTokenFromCategory($userId, $category);

        return $userData['interestedCategories'] ;
    }

    public function addUserSite($userId, $siteId)
    {

        $userRef = $this->database->getReference('users/' . $userId);
        $userData = $userRef->getValue();

        if (!isset($userData['interestedSites'])) {
            $userData['interestedSites'] = [];
        }

        if (!in_array($siteId, $userData['interestedSites'])) {
            $userData['interestedSites'][] = $siteId;
        }

        $userRef->update(['interestedSites' => $userData['interestedSites']]);

        $this->addUserExpoTokenToSite($userId, $siteId);

        return $userData['interestedSites'];
    }

    public function removeUserSite($userId, $siteId)
    {
        $userRef = $this->database->getReference('users/' . $userId);
        $userData = $userRef->getValue();

        if (!isset($userData['interestedSites'])) {
            return;
        }

        $index = array_search($siteId, $userData['interestedSites']);
        if ($index !== false) {
            unset($userData['interestedSites'][$index]);
        }

        $userRef->update(['interestedSites' => array_values($userData['interestedSites'])]);

        $this->removeUserExpoTokenFromSite($userId, $siteId);

        return $userData['interestedSites'];
    }



    private function addUserExpoTokenToCategory($userId, $category)
    {

        $userRef = $this->database->getReference('users/' . $userId);
        $userExpoToken = $userRef->getChild('expoPushToken/data')->getValue();


        if ($userExpoToken) {
            $categoryRef = $this->database->getReference('categories/' . $category . '/users');
            $usersInCategory = $categoryRef->getValue() ?? [];

            if (!in_array($userExpoToken, $usersInCategory)) {
                $usersInCategory[] = $userExpoToken;
            }

            $categoryRef->set($usersInCategory);
            $updatedCategory = $categoryRef->getValue();
            // print_r("Updated category users: ");
            // print_r($updatedCategory); // Debug print
        } else {
            print_r("User expo token not found for user ID: $userId"); // Debug print
        }
    }

    private function removeUserExpoTokenFromCategory($userId, $category)
    {
        $userRef = $this->database->getReference('users/' . $userId);
        $userExpoToken = $userRef->getChild('expoPushToken/data')->getValue();

        if ($userExpoToken) {
            $categoryRef = $this->database->getReference('categories/' . $category . '/users');
            $usersInCategory = $categoryRef->getValue() ?? [];

            if (($key = array_search($userExpoToken, $usersInCategory)) !== false) {
                unset($usersInCategory[$key]);
            }

            $categoryRef->set(array_values($usersInCategory));
        }
    }



    private function addUserExpoTokenToSite($userId, $siteId)
    {

        $userRef = $this->database->getReference('users/' . $userId);
        $userExpoToken = $userRef->getChild('expoPushToken/data')->getValue();


        if ($userExpoToken) {

            $siteRef = $this->database->getReference('sites/' . $siteId . '/users');
            $usersInSite = $siteRef->getValue() ?? [];

            if (!in_array($userExpoToken, $usersInSite)) {
                $usersInSite[] = $userExpoToken;
            }

            $siteRef->set($usersInSite);
        }
    }

    private function removeUserExpoTokenFromSite($userId, $siteId)
    {
        $userRef = $this->database->getReference('users/' . $userId);
        $userExpoToken = $userRef->getChild('expoPushToken/data')->getValue();

        if ($userExpoToken) {
            $siteRef = $this->database->getReference('sites/' . $siteId . '/users');
            $usersInSite = $siteRef->getValue() ?? [];

            if (($key = array_search($userExpoToken, $usersInSite)) !== false) {
                unset($usersInSite[$key]);
            }

            $siteRef->set(array_values($usersInSite));
        }
    }



    public function getExpoTokensForCategories(array $categories)
    {

        $parentCategoryNames = $this->getParentCategoryNames($categories);
        $categories = array_merge($categories, $parentCategoryNames);
        $allTokens = [];
    
        foreach ($categories as $category) {
            $categoryRef = $this->database->getReference('categories/' . $category . '/users');
            $expoTokens = $categoryRef->getValue() ?? [];
    
            $allTokens = array_merge($allTokens, $expoTokens);
        }
    
        $uniqueTokens = array_unique($allTokens);
    
        return $uniqueTokens;
    }

    public function getParentCategoryNames($categoryNames) {
        $parentCategoryNames = [];
        foreach ($categoryNames as $categoryName) {
            $category = get_category_by_slug($categoryName); 
            if ($category && $category->category_parent) {
                $parentCategory = get_category($category->category_parent); 
                $parentCategoryNames[] = $parentCategory->name; 
            }
        }
        return array_unique($parentCategoryNames); 
    }

    public function getExpoTokensForSiteAndCategories( array $categories, $siteId )
    {
        $siteTokensRef = $this->database->getReference('sites/' . $siteId . '/users');
        $siteTokensRef->getUri();

        $siteTokens = $siteTokensRef->getValue() ?? [];
    
        $allCategoryTokens = [];
    
        // הרחבת החיפוש לכלול גם קטגוריות אב
        $parentCategories = $this->getParentCategoryNames($categories);
        $allCategories = array_merge($categories, $parentCategories);
    
        foreach ($allCategories as $category) {
            $categoryRef = $this->database->getReference('categories/' . $category . '/users');
            $tokens = $categoryRef->getValue() ?? [];
            $allCategoryTokens = array_merge($allCategoryTokens, $tokens);
        }
    
        // מציאת החיתוך בין טוקני האתר לטוקני הקטגוריות
        $intersectTokens = array_intersect($siteTokens, $allCategoryTokens);
    
        // הסרת כפילויות
        $uniqueTokens = array_unique($intersectTokens);
    
        return $uniqueTokens;
    }

    public function getExpoTokensNoMorales(){
        $noMoralesRef = $this->database->getReference('moralecheckeds/users');
        $expoTokens = $noMoralesRef->getValue() ?? [];
        return $expoTokens;
    }



    public function getUserCategories($userId)
    {
        $userRef = $this->database->getReference('users/' . $userId);
        $userData = $userRef->getValue();

        if (isset($userData['interestedCategories'])) {
            return $userData['interestedCategories'];
        }

        return [];
    }

    public function setUserMoraleChecked($userId, $moraleChecked)
    {

        $userRef = $this->database->getReference('users/' . $userId);
        $userData = $userRef->getValue();
    
        $userRef->update(['moraleChecked' => $moraleChecked]);
        $this->addUserExpoTokenToMoraleCheckeds($userId, $moraleChecked);

        return;
    }

    private function addUserExpoTokenToMoraleCheckeds($userId, $moraleChecked)
    {

        $userRef = $this->database->getReference('users/' . $userId);
        $userExpoToken = $userRef->getChild('expoPushToken/data')->getValue();


        if ($userExpoToken) {

            $moralecheckedsRef = $this->database->getReference('moralecheckeds/users');
            $moralecheckeds = $moralecheckedsRef->getValue() ?? [];

            if ($moraleChecked == 'moralechecked') {
                if (!in_array($userExpoToken, $moralecheckeds)) {
                    $moralecheckeds[] = $userExpoToken;
                }
            }else{
                if (($key = array_search($userExpoToken, $moralecheckeds)) !== false) {
                    unset($moralecheckeds[$key]);
                }
            }
    
            $moralecheckedsRef->set($moralecheckeds);
        }
    }

    public function registerUserForAllCategoriesAndSites($userId) {



        // השגת כל הקטגוריות והאתרים 
        $categoriesRef = $this->database->getReference('categories');
        $sitesRef = $this->database->getReference('sites');
        $categories = array_keys($categoriesRef->getValue() ?? []);
        $sites = array_keys($sitesRef->getValue() ?? []);
    
        // עדכון המשתמש עם כל הקטגוריות והאתרים
        $userRef = $this->database->getReference('users/' . $userId);
        $userData = $userRef->getValue() ?? [];
        $userData['interestedCategories'] = $categories;
        $userData['interestedSites'] = $sites;
    
        $userRef->update($userData);


    
        // רישום Expo tokens לקטגוריות והאתרים
        foreach ($categories as $category) {
            $this->addUserExpoTokenToCategory($userId, $category);
        }
    
        foreach ($sites as $siteId) {
            $this->addUserExpoTokenToSite($userId, $siteId);
        }
    
        return json_encode([
            'categories' => $categories,
            'sites' => $sites,
    ]);
    }

    public function getUserSites($userId) {
        $userRef = $this->database->getReference('users/' . $userId);
        $userData = $userRef->getValue();
    
        if (isset($userData['interestedSites'])) {
            return $userData['interestedSites'];
        }
    
        return [];
    }
    
    public function getUserMoraleChecked($userId) {
        $userRef = $this->database->getReference('users/' . $userId);
        $userData = $userRef->getValue();
    
        if (isset($userData['moraleChecked'])) {
            return $userData['moraleChecked'];
        }
    
        return 'moralenochecked';
    }
    

}