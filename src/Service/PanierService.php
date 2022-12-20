<?php

namespace App\Service;

use App\Repository\MenuRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{

    public $session;

    public $repository;

    public $menuRepository;



    public function __construct(RequestStack $session, ProductRepository $repository, MenuRepository $menuRepository)
    {
        $this->session=$session;
        $this->repository=$repository;
        $this->menuRepository=$menuRepository;

    }

    public function addMenuEntree($id, $contenu)
    { $session = $this->session->getSession();
        $panier=$session->get('panier', []);
      $panier['menu'][$id]=['entree'=>$contenu,'plat'=>0,'dessert'=>0,'boisson'=>0];

         $session->set('panier', $panier);
    }

    public function addMenuPlat($id, $contenu)
    { $session = $this->session->getSession();
        $panier=$session->get('panier', []);
        if (isset($panier['menu'][$id])){
            $panier['menu'][$id]['plat']=$contenu;

        }else{
            $panier['menu'][$id]=['plat'=>$contenu,'dessert'=>0,'boisson'=>0];

        }


         $session->set('panier', $panier);
    }
    public function addMenuDessert($id, $contenu)
    { $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        $panier['menu'][$id]['dessert']=$contenu;


        $session->set('panier', $panier);
    }

    public function addMenuBoissons($id, $contenu)
    { $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        $panier['menu'][$id]['boisson']=$contenu;


        $session->set('panier', $panier);
    }

    public function addMenuBoisson($id, $contenu)
    { $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        $panier['menu'][$id]=['boisson'=>$contenu];


        $session->set('panier', $panier);
    }

    public function retraitPanierMenu($id)
    {

        $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        unset($panier['menu'][$id]);


        $session->set('panier', $panier);


    }



    public function add(int $id)
    {
        $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        if (empty($panier[$id])){

            $panier[$id]=1;
        }else{

            $panier[$id]++;

        }

        $session->set('panier', $panier);
    }

    public function remove(int $id)
    {
        $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        if (!empty($panier[$id]) && $panier[$id] !==1 ){

            $panier[$id]--;

        }else{

            unset($panier[$id]);

        }

        $session->set('panier', $panier);
    }


    public function delete(int $id)
    {
        $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        if (!empty($panier[$id]) ){

            unset($panier[$id]);
        }

        $session->set('panier', $panier);
    }

    public function destroy()
    {
        $session = $this->session->getSession();
        $panier=$session->get('panier', []);
        if (!empty($panier) ){

            unset($panier);
        }
        $session->set('panier', []);

    }

    public function getFullPanier()
    {
        $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        $panierDetail=[];

        foreach ($panier as $id => $quantite){

            if ($id!=='menu'){

                $panierDetail[]=[
                    'quantite'=>$quantite,
                    'produit'=>$this->repository->find($id)
                ];

            }


        }

        return $panierDetail;


    }

    public function getFullPanierMenu()
    {
        $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        $panierDetail=[];
   if (isset($panier['menu'])){

        foreach ($panier['menu'] as $id => $item){
             if (isset($panier['menu'][$id]['entree'])){
                 $panierDetail[]=[
                     'id'=>$id,
                     'nom'=>$this->menuRepository->find($id)->getNom(),
                     'entree'=>$this->repository->find($panier['menu'][$id]['entree']),
                     'plat'=>$this->repository->find($panier['menu'][$id]['plat']),
                     'dessert'=>$this->repository->find($panier['menu'][$id]['dessert']),
                     'boisson'=>$this->repository->find($panier['menu'][$id]['boisson']),
                     'prix'=>$this->menuRepository->find($id)->getPrix()
                 ];
             }
            if (!isset($panier['menu'][$id]['entree']) && isset($panier['menu'][$id]['plat'])){
                $panierDetail[]=[
                    'id'=>$id,
                    'nom'=>$this->menuRepository->find($id)->getNom(),
                    'plat'=>$this->repository->find($panier['menu'][$id]['plat']),
                    'dessert'=>$this->repository->find($panier['menu'][$id]['dessert']),
                    'boisson'=>$this->repository->find($panier['menu'][$id]['boisson']),
                    'prix'=>$this->menuRepository->find($id)->getPrix()
                ];
            }
            if (!isset($panier['menu'][$id]['entree']) && !isset($panier['menu'][$id]['plat'])){
                $panierDetail[]=[
                    'id'=>$id,
                    'nom'=>$this->menuRepository->find($id)->getNom(),
                    'boisson'=>$this->repository->find($panier['menu'][$id]['boisson']),
                    'prix'=>$this->menuRepository->find($id)->getPrix()
                ];
            }



        }

   }

        return $panierDetail;


    }




    public function getTotal()
    {

        $panier= $this->getFullPanier();
        $panierMenu=$this->getFullPanierMenu();



        $total=0;
        foreach ($panier as $indice => $item){
          if ($indice!=='menu'){

              $total+=$item['produit']->getPrix()*$item['quantite'];
          }

        }
        foreach ($panierMenu as $indice=>$valeur){

            $total+=$panierMenu[$indice]['prix'];
        }
        return $total;

    }












}