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


/**
 * @Route("/tricks")
 */
class TricksController extends AbstractController
{
    /**
     * @Route("/", name="tricks_index", methods={"GET"})
     */
    public function index(TricksRepository $tricksRepository): Response
    {
        return $this->render('tricks/index.html.twig', [
            'tricks' => $tricksRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tricks_new", methods={"GET","POST"})
     */
    public function new(Request $request , FileUploader $fileUploader)
    {
        $trick = new Tricks();

        $trick->setCreatedAt(new \DateTime());


        $form = $this->createForm(TricksType::class, $trick);


        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            $content = $form->get('content')->getData();

            $content_form = nl2br( $content );

            $trick->setContent($content_form);

            /**
             * @var UploadedFile $videos
             */

            $videos = $form->get('videos')->getData();

            foreach ($videos as $video){

                $trickVideos = new Video();

                $video->filename = str_replace("watch?v=","embed/", $video->filename );


                $trickVideos->setFilename($video->getFilename());



            }

            $images= $form->get('images')->getData();


            foreach ($images as $image){

                $filename = $fileUploader->upload($image);

                $trickImages = new Image();

                $trickImages->setFilename($filename);

                $trick->addImages($trickImages);


            }


            /**
             * @var UploadedFile $imageFile
             */

            $imageFile = $form->get('image')->getData();


            if ($imageFile) {


                $image= $fileUploader->upload($imageFile);

                $trick->setImage($image);

            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('tricks_index');
        }

        return $this->render('tricks/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tricks_show",  methods={"GET","POST"})
     */
    public function show(Tricks $trick ,CommentRepository $commentRepository, Request $request ): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        $comment->setCreatedAt(new \DateTime());

        $user = $this->getUser();

        $comment->setUser($user);

        $trickComment= $comment->setTrick($trick);

        $comment->getTrick($trickComment);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('tricks_show', [
                'id' => $trick->getId(),
        ]);
        }

        return $this->render('tricks/show.html.twig', [
            'comments' => $commentRepository->findBy(
                ['trick' => $trick],
                ['created_at' => 'DESC'],
            ),
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tricks_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tricks $trick ,  FileUploader $fileUploader): Response
    {
        
        $trick->content = str_replace("<br />", "", $trick->getContent("content"));

        $form = $this->createForm(TricksType::class, $trick);


        $trick->setUpdatedAt(new \DateTime());
        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $content = $form->get('content')->getData();

            $content_form = nl2br( $content );

            $trick->setContent($content_form);

            $imageFile = $form->get('image')->getData();


            if ($imageFile != null){

                $trick->setImage(


                    new File($this->getParameter('brochures_directory').'/'.$trick->getImage())

                );

                if ($imageFile) {


                    $image= $fileUploader->upload($imageFile);

                    $trick->setImage($image);

                }
            }

            $images= $form->get('images')->getData();

            if ($images){
                foreach ($images as $image){

                    $filename = $fileUploader->upload($image);

                    $trickImages = new Image();

                    $trickImages->setFilename($filename);

                    $trick->addImages($trickImages);


                }
            }



            /**
             * @var UploadedFile $videos
             */

            $videos = $form->get('videos')->getData();



            if($videos != null){

                foreach ($videos as $video){

                    $trickVideos = new Video();

                    $video->filename = str_replace("watch?v=","embed/", $video->filename );
                    
                    $trickVideos->setFilename($video->getFilename());

                }
            }



            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tricks_index');
        }

        return $this->render('tricks/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/tricks/deleteImage", name="tricks_delete_image", methods={"POST"})
     */
    public function deleteImage(Request $request , ImageRepository $imagerepository): Response
    {

        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $evenement = $imagerepository->find($id);

        $em->remove($evenement);
        $em->flush();


        return new JsonResponse($id);

    }

    /**
     * @Route("/tricks/deleteVideo", name="tricks_delete_video", methods={"POST"})
     */
    public function deleteVideo(Request $request , VideoRepository $videorepository): Response
    {

        $id = $request->request->get('id');

        $em = $this->getDoctrine()->getManager();
        $evenement = $videorepository->find($id);


        $em->remove($evenement);
        $em->flush();

        return new JsonResponse($id);

    }

    /**
     * @Route("/{id}", name="tricks_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tricks $trick , FileUploader $fileUploader): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $images =  $trick->getImages();

            foreach ($images as $image) {

                $image->getId();

                $fileUploader->removeUpload($image);

            }

            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tricks_index');
    }

    
    /**
     * @Route("delete/trick", name="tricks_delete_ajax",  methods={"GET","POST"})
     */
    public function deleteAjax(Request $request , TricksRepository $tricksRepository , FileUploader $fileUploader): Response
    {

            $id = $request->request->get('id');
            $em = $this->getDoctrine()->getManager();
            $evenement = $tricksRepository->find($id);



            $images =  $evenement->getImages();

            foreach ($images as $image) {

                $image->getId();

                $fileUploader->removeUpload($image);

            }

            $em->remove($evenement);
            $em->flush();



            return new JsonResponse($id);

    }


    /**
     * @Route("/tricks/editImage/{id}", name="tricks_edit_images",  methods={"GET","POST"})
     */

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


}
