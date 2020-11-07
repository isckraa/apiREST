<?php

namespace App\Controller;

use Serializable;

use App\Entity\Boutique;
use App\Repository\BoutiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ApiBoutiqueController extends AbstractController
{
    /**
     * @Route("/api/boutiques", name="api_boutique_list", methods={"GET"})
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function list( BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer )
    {
        $boutiques = $boutiqueRepository->findAll();
        $boutiqueSerializer = $this->boutiqueListSerializer( $serializer, $boutiques );

        return $this->json( $boutiqueSerializer, 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
    }


    /**
     * This function format data to return a list of boutique data.
     * @param SerialiserInterface $serializer
     * @param array $boutiques
     * @return array
     */
    public function boutiqueListSerializer( SerializerInterface $serializer, array $boutiques ) 
    {
        $boutiqueSerialize = [];
        $i = 0;

        foreach( $boutiques as $boutique ) {
            $boutiqueSerialize[$i] = json_decode( $serializer->serialize($boutique, 'json', [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ( $object ) {
                    return $object->getId();
                }
            ]), true );

            $i++;
        }

        return $boutiqueSerialize;
    }

    /**
     * @Route("/api/boutique/delete/{id}", name="api_boutique_delete", methods={"DELETE"})
     * @param Boutique $boutique
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete( Boutique $boutique, EntityManagerInterface $em )
    {
        try {
            $em->remove( $boutique );
            $em->flush();

            return $this->json( array(
                'status' => 201,
                'message' => "Store successfully deleted"
            ), 201, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"] );

        } catch( \Exception $e ) {
            return $this->json( array(
                'status' => 304,
                'message' => "Delete store failed. Error message : " . $e->getMessage()
            ), 304, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"] );
        }
    }

    /**
     * @Route("/api/boutique/create", name="api_boutique_create", methods={"POST"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create( EntityManagerInterface $em ) 
    {
        try {

            $newBoutique = new Boutique();
            $newBoutique->setNom( 'Test creation' );
            $newBoutique->setAdresse( '3 Rue Paris' );
            $newBoutique->setVille( 'Paris' );
            $newBoutique->setCodePostal( 75001 );

            $em->persist( $newBoutique );
            $em->flush();

            return $this->json( array(
                'status' => 201,
                'message' => "Store created with success."
            ), 201, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json'] );

        } catch ( NotEncodableValueException $e ) {
            return $this->json( array(
                'status' => 400,
                'message' => $e->getMessage()
            ), 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"] );
        }
    }

    /**
     * @Route("/api/boutique/update/{id}", name="api_boutique_update", methods={"POST", "PUT", "PATCH"})
     * @param Boutique $boutique
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update( Boutique $boutique, EntityManagerInterface $em )
    {
        try {
            $boutiqueEntity = $em->getRepository( Boutique::class )->find( $boutique );
            
            if ( $boutiqueEntity ) {
                try {
                    $boutiqueEntity->setAvis(10);
                    
                    $em->persist( $boutiqueEntity );
                    $em->flush();
            
                    return $this->json( array(
                        'status' => 201,
                        'message' => "Store updated with success."
                    ), 201, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json'] );
                } catch ( \Exception $e ) {
                    return $this->json( array(
                        'status' => 304,
                        'message' => "Update store failed with id=" . $boutiqueEntity->getId() .". Error : " . $e->getMessage()
                    ), 304, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json'] );
                } 
            }
        } catch ( \Exception $e ) {
            return $this->json( array(
                'status' => 400,
                'message' => "The store with the re-signed identifier does not exist."
            ), 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"] );
        }

    }


    /**
     * @Route("api/boutique/list/nom", name="api_boutique_list_nom", methods={"GET"})
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerialiserInterface $serializer
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findByNom( BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer, Request $request )
    {
        $boutiqueNom = $request->get('nom');

        if ( $boutiqueNom ) {
            $boutiques = $boutiqueRepository->findByNom( $boutiqueNom );
            $boutiqueSerialize = $this->boutiqueListSerializer( $serializer, $boutiques );

            return $this->json( $boutiqueSerialize, 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"] );
        } else {
            return $this->json( array( 
                'status' => 400,
                'message' => "Parameter nom is missing"
            ), 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        }
    }

    /**
     * @Route("/api/boutique/list/{id}", name="api_boutique_list_id", methods={"GET"})
     * @param Boutique $boutique
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findById( Boutique $boutique, SerializerInterface $serializer )
    {
        try {
            $response = json_decode( $serializer->serialize( $boutique, 'json', array(
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function( $object ) {
                    return $object->getId();
                }
            )), true );
    
            return $this->json( $response, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json'] );
        } catch ( \Exception $e ) {
            return $this->json( array(
                'status' => 400,
                'message' => 'Error : ' . $e->getMessage()
            ), 400, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json'] );
        }
    }
    
    /**
     * @Route("/api/boutique/sort/avis", name="api_boutique_sort_avis", methods={"GET"})
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sortByOpinion( BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer, Request $request )
    {
        $opinionMin = (int) $request->get('min');
        $opinionMax = (int) $request->get('max');

        try {
            $boutiques = $boutiqueRepository->findByOpinion( $opinionMin, $opinionMax );
            $boutiqueSerialize = $this->boutiqueListSerializer( $serializer, $boutiques );

            return $this->json( $boutiqueSerialize, 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"] );
        
        } catch( \Exception $e ) {    
            return $this->json( array( 
                'status' => 400,
                'message' => "Error : " . $e->getMessage()
            ), 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        }
    }
}
