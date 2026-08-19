<?php

class CommentaireController extends AbstractController
{
    public function add(): void
    {
        $this->requireLogin();
        $this->verifyCsrf();

        $content   = isset($_POST['content'])   ? trim($_POST['content'])   : '';
        $review_id = isset($_POST['review_id']) ? (int)$_POST['review_id'] : 0;
        $serie_id  = isset($_POST['serie_id'])  ? (int)$_POST['serie_id']  : 0;

        if ($content !== '' && $review_id > 0) {
            $cm = new CommentaireManager();
            $cm->add(new Commentaire($content, true, date('Y-m-d H:i:s'), null, $review_id, $_SESSION['user_id']));
        }

        if (($_POST['redirect'] ?? '') === 'home') {
            $this->redirect('');
        } else {
            $this->redirect('serie?id=' . $serie_id);
        }
    }
}
