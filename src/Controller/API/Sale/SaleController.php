<?php

declare(strict_types=1);

namespace App\Controller\API\Sale;

use App\Controller\ControllerHelper;
use App\Domain\Sale\SingService;
use App\Domain\Sale\UseCase\AddPayment\Command;
use App\Domain\Sale\UseCase\AddPayment\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/sale") */
class SaleController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/add/", name="addPayment", methods={"POST"}, options={"no_auth": true})
     * @param Request $request
     * @param Handler $handler
     * @return Response
     */
    public function addPayment(Request $request, Handler $handler, SingService $singService): Response
    {

        file_put_contents('data1.txt', json_encode($request->request->all()));
        /** @var Command $command */
        $command = new Command($request->request->all());
        file_put_contents('email.txt', $command->getEmail());
        file_put_contents('command.txt', json_encode($command));
//        if($singService->checkService($request->request->all())) {
        $payment = $handler->handle($command);
        file_put_contents('payment.txt', json_encode($payment));
        return $this->response($this->getSimpleSuccessResponse());
//        } else {
//            file_put_contents('sing.txt', 'no');
//        }

        $errorData = [
            'message' => 'Ошибка подписания платежа',
            'data' => $request->request->all()
        ];

        file_put_contents('error.txt', json_encode($errorData));
        return $this->response($this->getSimpleErrorResponse(['sign' => $errorData]));
    }
}