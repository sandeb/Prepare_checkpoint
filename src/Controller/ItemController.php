<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ItemManager;
use App\Model\PlanetManager;
use App\Model\MovieManager;


/**
 * Class ItemController
 *
 */
class ItemController extends AbstractController
{


    /**
     * Display item listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $itemManager = new ItemManager();
        $items = $itemManager->selectAll();

        return $this->twig->render('Item/index.html.twig', ['items' => $items]);
    }


    /**
     * Display item informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $itemManager = new ItemManager();
        $item = $itemManager->selectOneById($id);
        return $this->twig->render('Item/show.html.twig', ['item' => $item]);
    }


    /**
     * Display item edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $itemManager = new ItemManager();
        $item = $itemManager->selectOneById($id);

        $movieManager = new MovieManager();
        $movies = $movieManager->selectAll();

        $planetManager = new PlanetManager();
        $planets = $planetManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item = [
                'id' => $_POST['id'],
                'title' => $_POST['title'],
                'planet' => $_POST['planet'],
                'movie' => $_POST['movie']
            ];
            $itemManager->update($item);
            header('Location:/item/show/' . $id);
        }

        return $this->twig->render('Item/edit.html.twig', [
            'item' => $item,
            'movies' => $movies,
            'planets' => $planets
        ]);
    }


    /**
     * Display item creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemManager = new ItemManager();
            $item = [
                'title' => $_POST['title'],
            ];
            $id = $itemManager->insert($item);
            header('Location:/item/show/' . $id);
        }

        return $this->twig->render('Item/add.html.twig');
    }


    /**
     * Handle item deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $itemManager = new ItemManager();
        $itemManager->delete($id);
        header('Location:/item/index');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $movieManager = new MovieManager();
            $movie = [
                'title' => $_POST['movie'],
            ];
            
            $planetManager = new PlanetManager();
            $planet = [
                'name' => $_POST['planet'],
            ];
            $id_movie = $movieManager->insert($movie);
            $id_planet = $planetManager->insert($planet);
            
            if(isset($id_movie) && isset($id_planet)){
                $itemManager = new ItemManager();
                $item = [
                    'title' => $_POST['title'],
                    'planet_id' => $id_planet,
                    'movie_id' => $id_movie
                ];
                $itemManager->insert($item);
                header('Location:/item/index');
            }
            
        }
        return $this->twig->render('Item/create.html.twig');
    }
}
