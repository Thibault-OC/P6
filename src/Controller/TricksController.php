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
    public function index( TricksRepository $tricksRepository ): Response
    {
        $em = $this->getDoctrine()->getManager();

        $repoArticles = $em->getRepository(Tricks::class);

        $totalArticles = $repoArticles->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();


        return $this->render('tricks/index.html.twig', [
            'tricks' => $tricksRepository->findBy(
                array(), array('id' => 'DESC'),
                10

            ),
            'nbTricks' => $totalArticles,



        ]);
    }

    /**
     * @Route("/new", name="tricks_new", methods={"GET","POST"})
     */
    public function new(Request $request , FileUploader $fileUploader ,TricksRepository $tricksRepository)
    {
        $trick = new Tricks();

        $trick->setCreatedAt(new \DateTime());

        $form = $this->createForm(TricksType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $name_unique = $tricksRepository->findBy(
                ['title' => $form->get('title')->getData()]
            );



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


            if (empty($name_unique)){
                $entityManager->flush();
                $this->addFlash('success', 'Article Created!');

                return $this->redirectToRoute('tricks_index');
            }

            else{
                $this->addFlash('warning', 'This name trick already exists');
            }

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
                5
            ),
            'nbComments' =>count($trick->getTrick()),
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tricks_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tricks $trick ,  FileUploader $fileUploader , TricksRepository $tricksRepository): Response
    {
        
        $trick->content = str_replace("<br />", "", $trick->getContent("content"));

        $form = $this->createForm(TricksType::class, $trick);


        $trick->setUpdatedAt(new \DateTime());
        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name_unique = $tricksRepository->findBy(
                ['title' => $form->get('title')->getData(),]
            );


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


            if ($name_unique && $name_unique[0]->getId() == $trick->getId() ){
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'Article is updated !');

                return $this->redirectToRoute('tricks_index');
            }
            elseif (empty($name_unique)){
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'Article is updated !');

                return $this->redirectToRoute('tricks_index');
            }

            else{
                $this->addFlash('warning', 'This name trick already exists');
            }


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


    /**
     * @Route("/tricks/editVideo/{id}", name="tricks_edit_videos",  methods={"GET","POST"})
     */

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

    public function loadMore(Tricks $trick, CommentRepository $commentRepository, Request $request):JsonResponse
    {

        $datas = [];
        $depart = (int)$request->get('nbComment');
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

    public function moreTricks( TricksRepository $tricksRepository, Request $request):JsonResponse
    {

        $datas = [];
        $depart = (int)$request->get('nbTricks');
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

        }

        return new JsonResponse($datas);

    }


}
