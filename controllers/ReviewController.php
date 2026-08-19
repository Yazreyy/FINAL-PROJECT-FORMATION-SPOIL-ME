<?php

class ReviewController extends AbstractController {
    
private ReviewManager $rm;
private NoteManager $nm;

public function __construct()
{
    $this->rm = new ReviewManager();
    $this->nm = new NoteManager();
}

public function add() : void{
    $this->requireLogin();
    $this->verifyCsrf();

    if(isset($_POST['texte'])) {$texte = $_POST['texte'];} else {$texte = '';}
    if (isset($_POST['id_serie'])) {$id_serie = (int)$_POST['id_serie'];} else {$id_serie = 0;}
    if (isset($_POST['note'])) {$note = (int)$_POST['note'];} else {$note = 0;}

    $review = new Review($texte, false, date('Y-m-d H:i:s'), null, $id_serie, $_SESSION['user_id']);
    $this->rm->add($review);

    if ($note >= 1 && $note <= 5) {
        $existing = $this->nm->findByUserAndSerie($_SESSION['user_id'], $id_serie);
        if ($existing) {
            $existing->setValue($note);
            $this->nm->update($existing);
        } else {
            $this->nm->add(new Note($note, date('Y-m-d H:i:s'), null, $_SESSION['user_id'], $id_serie));
        }
    }

    $this->redirect('serie?id=' . $id_serie);
}

public function delete() : void {
    $this->requireLogin();
    $this->verifyCsrf();

    if(isset($_GET['id'])) {$id = (int)$_GET['id'];} else {$id = 0;}
    if(isset($_GET['id_serie'])) {$id_serie = (int)$_GET['id_serie'];} else {$id_serie = 0;}

    $review = $this->rm->findOne($id);
    if ($review && $review->getUserId() === $_SESSION['user_id']) {
        $this->rm->delete($id);
    }

    if (isset($_GET['redirect']) && $_GET['redirect'] === 'profile') {
        $this->redirect('profile');
    } else {
        $this->redirect('serie?id=' . $id_serie);
    }
}
}