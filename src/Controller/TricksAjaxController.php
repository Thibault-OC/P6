<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Video;
use App\Entity\Image;
use App\Entity\Comment;
use App\Form\TricksType;
use App\Repository\TricksRepository;
use App\Repository\ImageRepository;
use App\Repository\VideoRepository;
use App\Service\FileUploader;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Cocur\Slugify\Slugify;



/**
 * @Route("/Tricks")
 */
class TricksAjaxController extends AbstractController
{

    /**
     * @Route("delete/trick", name="tricks_delete_ajax",  methods={"GET","POST"})
     */
    /*Route supression d'un trick avec ajax*/
    public function deleteAjax(Request $request , TricksRepository $tricksRepository , FileUploader $fileUploader): Response
    {

        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $evenement = $tricksRepository->find($id);

        /* vérification avant supression l'utilisateur doit etre l'autre du trick */

        if($evenement->getUser($id) == $this->getUser()){

            $images =  $evenement->getImages();

            foreach ($images as $image) {

                $image->getId();

                $fileUploader->removeUpload($image);

            }

            $em->remove($evenement);
            $em->flush();

            $message="Trick has been deleted";
            $alert="alert-success";

            return new JsonResponse(array(
                'id' => $id,
                'message' => $message,
                'alert' => $alert,
            ));
        }
        else{

            $message="you are not the creator you cannot deleted";
            $alert="alert-warning";
            $id=0;

            return new JsonResponse(array(
                'id' => $id,
                'message' => $message,
                'alert' => $alert,
            ));
        }


    }


    /**
     * @Route("/tricks/editImage/{id}", name="tricks_edit_images",  methods={"GET","POST"})
     */

    /* route modification d'un image avec ajax */

    public function editImage(Request $request ,Tricks $trick ,ImageRepository $imagerepository , FileUploader $fileUploader): Response
    {


        $id = $request->request->get('id');

        $em = $this->getDoctrine()->getManager();

        $evenement = $imagerepository->find($id);

        $evenement = $fileUploader->removeUpload($evenement);

        $em->remove($evenement);


        $file = $request->files->get('image');



        if(!is_null($file)) {

            $file = $fileUploader->upload($file);

            $trickImages = new Image();


            $trickImages->setFilename($file);

            $trick->addImages($trickImages);

            $this->getDoctrine()->getManager()->flush();

            return new Response(json_encode(array("id_new" => $trickImages->getId() , "name" => $trickImages->getFilename() , "id"=>$id)));


        }




    }


    /**
     * @Route("/tricks/editVideo/{id}", name="tricks_edit_videos",  methods={"GET","POST"})
     */
    /* Route modification d'une video avec ajax */

    public function editVideo(Request $request ,Tricks $trick ,VideoRepository $videorepository ): Response
    {


        $id = $request->request->get('id');

        $em = $this->getDoctrine()->getManager();
        $evenement = $videorepository->find($id);


        $em->remove($evenement);


        $file = $request->request->get('video');


        if(!is_null($file)) {


            $trickVideos = new Video();

            $file = str_replace("watch?v=","embed/", $file );

            $trickVideos->setFilename($file);

            $trick->addVideo($trickVideos);

            $this->getDoctrine()->getManager()->flush();

            return new Response(json_encode(array("id_new" => $trickVideos->getId() , "name" => $trickVideos->getFilename() , "id"=>$id)));


        }


    }



    /**
     * @Route("/{id}/more", name="more_comments" , methods={"GET","POST"})
     */

    /* Route pagination des commentaire d'un trick avec ajax*/

    public function loadMore(Tricks $trick, CommentRepository $commentRepository, Request $request):JsonResponse
    {

        $datas = [];
        $depart = (int)$request->get('nbComment');
        /* pagination 5 par 5 des commentaires */
        $nbEnplus = 5;


        //Pour tester dans des conditions Web
        //sleep(5);

        $listeMoreComment = $commentRepository->findBy(
            ['trick'=>$trick->getId()],
            ['id'=>'DESC'],
            $nbEnplus,
            $depart
        );


        foreach ( $listeMoreComment as $key => $item) {
            $datas[$key]['id'] = $item->getId();
            $datas[$key]['content'] = htmlspecialchars(nl2br($item->getComment()));
            $datas[$key]['createdAt'] = $item->getCreatedAt()->format('d/m/Y');
            $datas[$key]['user'] = $item->getUser()->getFirstname();
        }

        return new JsonResponse($datas);

    }


    /**
     * @Route("/tricks/moretricks", name="more_tricks" , methods={"GET","POST"})
     */

    /* Route pagination des tricks avec ajax*/

    public function moreTricks( TricksRepository $tricksRepository, Request $request):JsonResponse
    {

        $datas = [];
        $depart = (int)$request->get('nbTricks');
        $user = $this->getUser();
        /* pagination 5 par 5 des tricks */
        $nbEnplus = 10;


        //Pour tester dans des conditions Web
        //sleep(5);

        $listeMoreTricks = $tricksRepository->findBy(
            array(), array('id' => 'DESC'),
            $nbEnplus,
            $depart

        );



        foreach ( $listeMoreTricks as $key => $item) {
            $datas[$key]['id'] = $item->getId();
            $datas[$key]['name'] = $item->getTitle();
            $datas[$key]['image'] = $item->getImage();
            $datas[$key]['slug'] = $item->getslug();
            $datas[$key]['user'] = $user;



        }

        return new JsonResponse($datas);

    }


}