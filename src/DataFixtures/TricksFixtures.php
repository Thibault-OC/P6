<?php

namespace App\DataFixtures;

use App\Entity\Tricks;
use App\Entity\Image;
use App\Entity\Video;
use Faker;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Cocur\Slugify\Slugify;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');


        $contents = [
            1=>[
                'title' => 'Les grabs - mute',
                'content' => "Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. »<br /><br />Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l\'effectuer, avec des difficultés variables :<br />
                                <br />
                                mute : saisie de la carre frontside de la planche entre les deux pieds avec la main avant ;<br />
                                <br />
                                Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »)."
            ],
            2=>[
                'title' => 'Les grabs - sad',
                'content' => "Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. »<br />
                                <br />
                                Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l'effectuer, avec des difficultés variables :<br />
                                <br />
                                sad ou melancholie ou style week : saisie de la carre backside de la planche, entre les deux pieds, avec la main avant ;<br />
                                <br />
                                Un grab est d'autant plus réussi que la saisie est longue. De plus, le saut est d'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »)."
            ],
            3=>[
                'title' => 'Les grabs - indy',
                'content' => "Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. »<br />
                                <br />
                                Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l'effectuer, avec des difficultés variables :<br />
                                <br />
                                - indy : saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière ;"
            ],
            4=>[
                'title' => 'Les grabs - stalefish',
                'content' => "Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. »<br />
                                <br />
                                Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l'effectuer, avec des difficultés variables :<br />
                                <br />
                                -stalefish : saisie de la carre backside de la planche entre les deux pieds avec la main arrière ;<br />
                                <br />
                                Un grab est d'autant plus réussi que la saisie est longue. De plus, le saut est d'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »)."

            ],
            5=>[
                'title' => 'Les grabs - tail grab',
                'content' => "Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. »<br />
                                <br />
                                Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l'effectuer, avec des difficultés variables :<br />
                                <br />
                                - tail grab : saisie de la partie arrière de la planche, avec la main arrière ;<br />
                                <br />
                                Un grab est d'autant plus réussi que la saisie est longue. De plus, le saut est d'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »)."
            ],
            6=>[
                'title' => 'Les grabs - nose grab',
                'content' => "Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. »<br />
                                <br />
                                Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l'effectuer, avec des difficultés variables :<br />
                                <br />
                                - nose grab : saisie de la partie avant de la planche, avec la main avant ;<br />
                                <br />
                                Un grab est d'autant plus réussi que la saisie est longue. De plus, le saut est d'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »)."

            ],
            7=>[
                'title' => 'Les rotations - un 180',
                'content' => "On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d'effectuer une rotation horizontale pendant le saut, puis d'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués :<br />
                                <br />
                                - un 180 désigne un demi-tour, soit 180 degrés d'angle ;<br />
                                <br />
                                Une rotation peut être frontside ou backside : une rotation frontside correspond à une rotation orientée vers la carre backside. Cela peut paraître incohérent mais l'origine étant que dans un halfpipe ou une rampe de skateboard, une rotation frontside se déclenche naturellement depuis une position frontside (i.e. l'appui se fait sur la carre frontside), et vice-versa. Ainsi pour un rider qui a une position regular (pied gauche devant), une rotation frontside se fait dans le sens inverse des aiguilles d'une montre.<br />
                                <br />
                                Une rotation peut être agrémentée d'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu'un autre. Les rotations de plus de trois tours existent mais sont plus rares, d'abord parce que les modules assez gros pour lancer un tel saut sont rares, et ensuite parce que la vitesse de rotation est tellement élevée qu'un grab devient difficile, ce qui rend le saut considérablement moins esthétique.<br />
                                <br />
                                Pour entrer sur une barre de slide, le rideur peut se mettre perpendiculaire à l'axe de la barre et fera donc un quart de tour en l'air, modulo 360 degrés — il est possible de faire n tours complets plus un quart de tour. On a donc la dénomination suivante pour ce type de rotations :<br />
                                <br />
                                90 pour un quart de tour simple ;<br />
                                270 pour trois quarts de tours ;<br />
                                450 pour un tour un quart ;<br />
                                630 pour un tour trois quarts ;<br />
                                810 pour deux tours un quart ;<br />
                                etc."

            ],
            8=>[
                'title' => 'Les rotations - un 360',
                'content' => "On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d'effectuer une rotation horizontale pendant le saut, puis d'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués :<br />
                                <br />
                                - 360, trois six pour un tour complet ;<br />
                                <br />
                                Une rotation peut être frontside ou backside : une rotation frontside correspond à une rotation orientée vers la carre backside. Cela peut paraître incohérent mais l'origine étant que dans un halfpipe ou une rampe de skateboard, une rotation frontside se déclenche naturellement depuis une position frontside (i.e. l'appui se fait sur la carre frontside), et vice-versa. Ainsi pour un rider qui a une position regular (pied gauche devant), une rotation frontside se fait dans le sens inverse des aiguilles d'une montre.<br />
                                <br />
                                Une rotation peut être agrémentée d'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu'un autre. Les rotations de plus de trois tours existent mais sont plus rares, d'abord parce que les modules assez gros pour lancer un tel saut sont rares, et ensuite parce que la vitesse de rotation est tellement élevée qu'un grab devient difficile, ce qui rend le saut considérablement moins esthétique.<br />
                                <br />
                                Pour entrer sur une barre de slide, le rideur peut se mettre perpendiculaire à l'axe de la barre et fera donc un quart de tour en l'air, modulo 360 degrés — il est possible de faire n tours complets plus un quart de tour. On a donc la dénomination suivante pour ce type de rotations :<br />
                                <br />
                                90 pour un quart de tour simple ;<br />
                                270 pour trois quarts de tours ;<br />
                                450 pour un tour un quart ;<br />
                                630 pour un tour trois quarts ;<br />
                                810 pour deux tours un quart ;<br />
                                etc."

            ],
            9=>[
                'title' => 'Les rotations - un 540',
                'content' => "On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d'effectuer une rotation horizontale pendant le saut, puis d'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués :<br />
                                <br />
                                -540, cinq quatre pour un tour et demi ;<br />
                                <br />
                                Une rotation peut être frontside ou backside : une rotation frontside correspond à une rotation orientée vers la carre backside. Cela peut paraître incohérent mais l'origine étant que dans un halfpipe ou une rampe de skateboard, une rotation frontside se déclenche naturellement depuis une position frontside (i.e. l'appui se fait sur la carre frontside), et vice-versa. Ainsi pour un rider qui a une position regular (pied gauche devant), une rotation frontside se fait dans le sens inverse des aiguilles d'une montre.<br />
                                <br />
                                Une rotation peut être agrémentée d'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu'un autre. Les rotations de plus de trois tours existent mais sont plus rares, d'abord parce que les modules assez gros pour lancer un tel saut sont rares, et ensuite parce que la vitesse de rotation est tellement élevée qu'un grab devient difficile, ce qui rend le saut considérablement moins esthétique.<br />
                                <br />
                                Pour entrer sur une barre de slide, le rideur peut se mettre perpendiculaire à l'axe de la barre et fera donc un quart de tour en l'air, modulo 360 degrés — il est possible de faire n tours complets plus un quart de tour. On a donc la dénomination suivante pour ce type de rotations :<br />
                                <br />
                                90 pour un quart de tour simple ;<br />
                                270 pour trois quarts de tours ;<br />
                                450 pour un tour un quart ;<br />
                                630 pour un tour trois quarts ;<br />
                                810 pour deux tours un quart ;<br />
                                etc."

            ],
            10=>[
                'title' => 'Les rotations - un 900',
                'content' => "On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d'effectuer une rotation horizontale pendant le saut, puis d'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués :<br />
                                <br />
                                - 900 pour deux tours et demi ;<br />
                                <br />
                                Une rotation peut être frontside ou backside : une rotation frontside correspond à une rotation orientée vers la carre backside. Cela peut paraître incohérent mais l'origine étant que dans un halfpipe ou une rampe de skateboard, une rotation frontside se déclenche naturellement depuis une position frontside (i.e. l'appui se fait sur la carre frontside), et vice-versa. Ainsi pour un rider qui a une position regular (pied gauche devant), une rotation frontside se fait dans le sens inverse des aiguilles d'une montre.<br />
                                <br />
                                Une rotation peut être agrémentée d'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu'un autre. Les rotations de plus de trois tours existent mais sont plus rares, d'abord parce que les modules assez gros pour lancer un tel saut sont rares, et ensuite parce que la vitesse de rotation est tellement élevée qu'un grab devient difficile, ce qui rend le saut considérablement moins esthétique.<br />
                                <br />
                                Pour entrer sur une barre de slide, le rideur peut se mettre perpendiculaire à l'axe de la barre et fera donc un quart de tour en l'air, modulo 360 degrés — il est possible de faire n tours complets plus un quart de tour. On a donc la dénomination suivante pour ce type de rotations :<br />
                                <br />
                                90 pour un quart de tour simple ;<br />
                                270 pour trois quarts de tours ;<br />
                                450 pour un tour un quart ;<br />
                                630 pour un tour trois quarts ;<br />
                                810 pour deux tours un quart ;<br />
                                etc."

            ],
            11=>[
                'title' => 'Les rotations - un 1080',
                'content' => "On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d'effectuer une rotation horizontale pendant le saut, puis d'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués :<br />
                                <br />
                                -1080 ou big foot pour trois tours ;<br />
                                <br />
                                Une rotation peut être frontside ou backside : une rotation frontside correspond à une rotation orientée vers la carre backside. Cela peut paraître incohérent mais l'origine étant que dans un halfpipe ou une rampe de skateboard, une rotation frontside se déclenche naturellement depuis une position frontside (i.e. l'appui se fait sur la carre frontside), et vice-versa. Ainsi pour un rider qui a une position regular (pied gauche devant), une rotation frontside se fait dans le sens inverse des aiguilles d'une montre.<br />
                                <br />
                                Une rotation peut être agrémentée d'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu'un autre. Les rotations de plus de trois tours existent mais sont plus rares, d'abord parce que les modules assez gros pour lancer un tel saut sont rares, et ensuite parce que la vitesse de rotation est tellement élevée qu'un grab devient difficile, ce qui rend le saut considérablement moins esthétique.<br />
                                <br />
                                Pour entrer sur une barre de slide, le rideur peut se mettre perpendiculaire à l'axe de la barre et fera donc un quart de tour en l'air, modulo 360 degrés — il est possible de faire n tours complets plus un quart de tour. On a donc la dénomination suivante pour ce type de rotations :<br />
                                <br />
                                90 pour un quart de tour simple ;<br />
                                270 pour trois quarts de tours ;<br />
                                450 pour un tour un quart ;<br />
                                630 pour un tour trois quarts ;<br />
                                810 pour deux tours un quart ;<br />
                                etc."

            ],
            12=>[
                'title' => 'Les flips',
                'content' => "Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière.<br />
                                <br />
                                Il est possible de faire plusieurs flips à la suite, et d'ajouter un grab à la rotation.<br />
                                <br />
                                Les flips agrémentés d'une vrille existent aussi (Mac Twist, Hakon Flip, ...), mais de manière beaucoup plus rare, et se confondent souvent avec certaines rotations horizontales désaxées.<br />
                                <br />
                                Néanmoins, en dépit de la difficulté technique relative d'une telle figure, le danger de retomber sur la tête ou la nuque est réel et conduit certaines stations de ski à interdire de telles figures dans ses snowparks."

            ],
            13=>[
                'title' => 'Les rotations désaxées',
                'content' => "Une rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation. Il existe différents types de rotations désaxées (corkscrew ou cork, rodeo, misty, etc.) en fonction de la manière dont est lancé le buste. Certaines de ces rotations, bien qu'initialement horizontales, font passer la tête en bas.<br />
                                <br />
                                Bien que certaines de ces rotations soient plus faciles à faire sur un certain nombre de tours (ou de demi-tours) que d'autres, il est en théorie possible de d'attérir n'importe quelle rotation désaxée avec n'importe quel nombre de tours, en jouant sur la quantité de désaxage afin de se retrouver à la position verticale au moment voulu.<br />
                                <br />
                                Il est également possible d'agrémenter une rotation désaxée par un grab."

            ],
            14=>[
                'title' => 'Les slides',
                'content' => "Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé.<br />
                                <br />
                                On peut slider avec la planche centrée par rapport à la barre (celle-ci se situe approximativement au-dessous des pieds du rideur), mais aussi en nose slide, c'est-à-dire l'avant de la planche sur la barre, ou en tail slide, l'arrière de la planche sur la barre."

            ],
            15=>[
                'title' => 'Les one foot tricks',
                'content' => "Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante pour mettre en évidence le fait que le pied n'est pas fixé. Ce type de figure est extrêmement dangereuse pour les ligaments du genou en cas de mauvaise réception."

            ],
            16=>[
                'title' => 'Old school',
                'content' => "Le terme old school désigne un style de freestyle caractérisée par en ensemble de figure et une manière de réaliser des figures passée de mode, qui fait penser au freestyle des années 1980 - début 1990 (par opposition à new school) :<br />
                                <br />
                                figures désuètes : Japan air, rocket air, ...<br />
                                rotations effectuées avec le corps tendu<br />
                                figures saccadées, par opposition au style new school qui privilégie l'amplitude<br />
                                À noter que certains tricks old school restent indémodables :<br />
                                <br />
                                Backside Air<br />
                                Method Air"

            ],
        ];



            for ($nbAnnonces = 1; $nbAnnonces <= 16; $nbAnnonces++) {

                $user = $this->getReference('user_'.$faker->numberBetween(1, 5));
                $categorie = $this->getReference('categorie_'.$faker->numberBetween(1, 4));

                $annonce = new Tricks();
                $slugify = new Slugify();
                $slug = $slugify->slugify($contents[$nbAnnonces]['title'], ['separator' => '-']);
                $annonce->setUser($user);
                $annonce->setCategory($categorie);
                $annonce->setTitle($contents[$nbAnnonces]['title']);
                $annonce->setContent($contents[$nbAnnonces]['content']);
                $annonce->setCreatedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
                $annonce->setSlug($slug);

                $nomImg = "principale.jpeg";

                $annonce->setImage($nomImg);


                for ($image = 1; $image <= 3; $image++) {

                    $content = file_get_contents("public/uploads/images/image_".$image.".jpeg");
                    $fp = fopen("public/uploads/images/image_".$nbAnnonces."_".$image.".jpeg", "w");
                    fwrite($fp, $content);
                    fclose($fp);
                    $nomImg = "image_".$nbAnnonces."_".$image.".jpeg";
                    $imageAnnonce = new Image();
                    $imageAnnonce->setFilename($nomImg);
                    $annonce->addImages($imageAnnonce);
                }

                for ($video = 1; $video <= 3; $video++) {
                    if ($video == 1) {
                        $nomVideo = "https://www.youtube.com/embed/M_BOfGX0aGs";
                    } elseif ($video == 2) {
                        $nomVideo = "https://www.youtube.com/embed/OhGMklaejcY";
                    } elseif ($video == 3) {
                        $nomVideo = "https://www.youtube.com/embed/oI-umOzNBME";
                    }

                    $videoAnnonce = new Video();
                    $videoAnnonce->setFilename($nomVideo);
                    $annonce->addVideo($videoAnnonce);
                }


                $manager->persist($annonce);

                $this->addReference('trick_'.$nbAnnonces, $annonce);

            }


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UsersFixtures::class
        ];
    }
}
