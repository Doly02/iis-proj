<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;
use Tracy\Debugger;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new \Nette\Application\Routers\RouteList();

        $router->addRoute('conference/conference-list/list', ':ConferenceModule:ConferenceList:list');

        $router->addRoute('conference/conference-list-user/list', ':ConferenceModule:ConferenceListUser:list');

        $router->addRoute('reservation/non-registered/make-reservation', ':ReservationModule:ReserveNonRegistered:makeReservation');

        //$router->addRoute('user/sign-out', ':CommonModule:Base:signOut');

        $router->addRoute('user/sign-out', ':UserModule:Authentication:signOut');

        $router->addRoute('reservation/non-registered/make-reservation', ':ReservationModule:ReserveRegistered:makeReservation');

        $router->addRoute('conference/conference-add/add', ':ConferenceModule:ConferenceAdd:add');

        $router->addRoute('conference/edit-conference/edit', ':ConferenceModule:EditConference:edit');

        $router->addRoute('user/aregistration', ':UserModule:CreateAdmin:default');

        $router->addRoute('conference/add-room-to-conference/add', ':ConferenceModule:AddRoomToConference:add');

        $router->addRoute('user/userlist/list', ':UserList:UserList:list');

        $router->addRoute('room/room-add/add', ':RoomModule:RoomAdd:add');

        $router->addRoute('room/room-edit/edit', ':RoomModule:EditRoom:editRoom');

        $router->addRoute('reservation/reservation-list/list', ':ReservationModule:ReservationList:list');

        $router->addRoute('reservation/reservation-detail/default', ':ReservationModule:ReservationDetail:default');


        $router->addRoute('presentation/presentation-add/add', ':PresentationModule:PresentationAdd:add');

        $router->addRoute('presentation/presentation-list/list', ':PresentationModule:PresentationList:list');

        $router->addRoute('presentation/presentation-list/organizer-list', ':PresentationModule:PresentationList:organizerList');

        $router->addRoute('presentation/presentation-detail/default', ':PresentationModule:PresentationDetail:default');

        // $router->addRoute('user/edit-user', ':UserModule:EditUser:editUser');

        $router->addRoute('user/authentication/sign-in', ':UserModule:Authentication:signIn');

        $router->addRoute('user/register/default', ':UserModule:Register:default');


        $router->addRoute('lecture/add-lecture/default', ':LectureModule:AddNewLecture:default');

        $router->addRoute('user/register/admin', ':UserModule:CreateAdmin:default');
        $router->addRoute('lecture/detail/<lectureId>', 'LectureModule:LectureDetail:default');






        $router[] = new \Nette\Application\Routers\Route('[<module>[/<presenter>[/<action>[/<id \d+>]]]]', [
            'module' => 'CommonModule',
            'presenter' => 'Home',
            'action' => 'default',
        ]);

        return $router;
    }
}
