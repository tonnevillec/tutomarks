<?php
namespace App\Controller\Api;

use App\Entity\Comments;
use Symfony\Component\Security\Core\Security;

class CommentCreateController
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Comments $data)
    {
        $data->setUser($this->security->getUser());
        return $data;
    }
}