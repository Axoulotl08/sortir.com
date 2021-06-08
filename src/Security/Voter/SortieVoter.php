<?php

namespace App\Security\Voter;

use App\Entity\Sortie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class SortieVoter extends Voter
{

    const SORTIE_EDIT = "sortie_edit";
    const SORTIE_CANCELLED = "sortie_cancelled";

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $sortie): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::SORTIE_EDIT, self::SORTIE_CANCELLED])
            && $sortie instanceof \App\Entity\Sortie;
    }

    protected function voteOnAttribute(string $attribute, $sortie, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::SORTIE_EDIT:
                // on vérifie si on peu éditer
                dump($this->canEdit($sortie, $user));
                return $this->canEdit($sortie, $user);
                break;
            case self::SORTIE_CANCELLED:
                //on vérifie si on peu supprimer
                return $this->canCancel($sortie, $user);
                break;
        }

        return false;
    }

    private function canEdit(Sortie $sortie, User $user)
    {
        //L'organisateur de la sortie peur la modifier
        return $user->getParticipant()->getId() == $sortie->getOrganisateur()->getId();
    }

    private function canCancel(Sortie $sortie, User $user)
    {
        //L'organisateur ou un admin peu annulée une sortie
        if($this->security->isGranted('ROLE_ADMIN')) return true;
        else return $user->getParticipant()->getId() == $sortie->getOrganisateur()->getId();
    }
}
