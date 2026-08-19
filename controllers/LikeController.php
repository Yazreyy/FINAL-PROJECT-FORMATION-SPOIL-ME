<?php

class LikeController extends AbstractController
{
    public function toggle(): void
    {
        $this->requireLogin();
        $this->verifyCsrf();

        $review_id = isset($_POST['review_id']) ? (int)$_POST['review_id'] : 0;
        $serie_id  = isset($_POST['serie_id'])  ? (int)$_POST['serie_id']  : 0;

        $lm = new LikeManager();
        $liked = false;

        if ($review_id > 0) {
            if ($lm->exists($_SESSION['user_id'], $review_id)) {
                $lm->remove($_SESSION['user_id'], $review_id);
                $liked = false;
            } else {
                $lm->add($_SESSION['user_id'], $review_id);
                $liked = true;
            }
        }

        if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['liked' => $liked, 'count' => $lm->count($review_id)]);
            return;
        }

        if (($_POST['redirect'] ?? '') === 'home') {
            $this->redirect('');
        } else {
            $this->redirect('serie?id=' . $serie_id);
        }
    }
}
