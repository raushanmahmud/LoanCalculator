<?php
    namespace App\Controller;

    
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use App\Entity\Loan;
    use App\Entity\Payment;

    use Symfony\Component\Form\Extension\Core\Type\TextType;    
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;    
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\MoneyType;
    use Symfony\Component\Form\Extension\Core\Type\PercentType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;


    class LoanController extends AbstractController  {
        /**
         * @Route("/", name="loan_list")
         * @Method({"GET"})
         */
        public function index(){
            //return new Response('<html><body>Hello World</body></html>');
            $loans = $this->getDoctrine()->getRepository(Loan::class)->findAll();
            return $this->render('loans/index.html.twig', array('loans'=> $loans));
        }

        

        /**
         * @Route("/loan/new", name="new_loan")
         * @Method({"GET", "POST"})
         */
        public function new(Request $request){
            $loan = new Loan();

            $form = $this->createFormBuilder($loan)
                    ->add('title', TextType::class, array('attr'=>array(
                        'class' => 'form-control'
                    )))
                    ->add('body', TextareaType::class, array(
                        'required'=>false,
                        'attr'=>array('class' => 'form-control mb-3'
                    )))
                    ->add('startDate', DateType::class, [
                        'widget' => 'choice',
                        'format' => 'yyyy-MM-dd',
                        // prevents rendering it as type="date", to avoid HTML5 date pickers
                        'html5' => false,
                    
                        // adds a class that can be selected in JavaScript
                        'attr' => ['class' => 'js-datepicker mb-3'],
                    ])
                    ->add('amount', MoneyType::class, [
                        'label'=>'Amount in ',
                        'currency' => 'KZT',
                        'attr'=>array('class' => 'form-control mb-3'
                        
                    )])
                    ->add('months', IntegerType::class, [
                        'attr' => ['class' => 'form-control tinymce mb-3'],
                    ])
                    ->add('yearlyInterestRate', PercentType::class, [
                        'type'=>'integer',
                        'attr'=>array('class' => 'form-control'
                        
                    )])
                    ->add('save', SubmitType::class, array(
                        'label'=>'Create',
                        'attr'=>array('class' => 'btn btn-primary mt-3'
                    )))
                    ->getForm();

                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()){
                        $loan = $form->getData();

                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($loan);
                        $entityManager->flush();

                        $myDate = $loan->getStartDate();
                        $myMonths = $loan->getMonths();
                        $myAmount = $loan->getAmount();
                        $myYearlyInterestRate = $loan->getYearlyInterestRate();
                        
                        $myData = $this->getPaymentScheduleData($myDate, $myMonths, $myAmount, $myYearlyInterestRate);
                        
                        $data = $myData;

                        for ($i=0; $i<$myMonths; $i=$i+1){
                            $monthlyPaymentAmountForPay = $data['monthlyPaymentAmountsForPay'][$i];
                            $monthlyInterestAmountForPay = $data['monthlyInterestAmountsForPay'][$i];
                            $monthlyBasePaymentAmountForPay = $data['monthlyBasePaymentAmountsForPay'][$i];
                            $remainingBaseAmountAfterPay = $data['remainingBaseAmountsAfterPay'][$i];
                            $scheduledDate = $data['scheduledDates'][$i];
                            $remainingBaseAmountBeforePay = $data['remainingBaseAmountsBeforePay'][$i];
                            
                            $payment = new Payment();
                            $payment->setFullAmountForPay($monthlyPaymentAmountForPay);
                            $payment->setInterestAmountForPay($monthlyInterestAmountForPay);
                            $payment->setBaseAmountForPay($monthlyBasePaymentAmountForPay);
                            $payment->setRemainingBaseAmountBeforePay($remainingBaseAmountBeforePay);
                            $payment->setRemainingBaseAmountAfterPay($remainingBaseAmountAfterPay);
                            $payment->setScheduledDate($scheduledDate);

                            $payment->setLoan($loan);

                            $entityManager = $this->getDoctrine()->getManager();
                            
                            $entityManager->persist($payment);
                            $entityManager->flush();
                        }

                        return $this->redirectToRoute('loan_show', array('id'=>$loan->getId())) ;
                    }

                    return $this->render('loans/new.html.twig', array(
                        'form' => $form->createView()
                    ));

        }
        public function getPaymentScheduleData($startDate, $months, $amount, $yearlyInterestRate) {
            $date = $startDate->format('Y-m-d');
            
            $data = array();
            
            // date, amount, months, yearlyInterestRate
            
            
            $date = explode('-', $date);
            $fixedDay = $date[2];
            $date = implode('-', $date);
            
            $currentDate = date('Y-m-d', strtotime($date));
            
            for ($i = 0; $i<$months; $i++){
                $isProcessed = false;
                // check for month ends
                $fixedDayN = (int)$fixedDay;
                if ($fixedDayN>28) {
                    $date = explode('-', $currentDate);
                    $changed_date = array();
                    $changed_date[0] = $date[0];
                    $changed_date[1] = $date[1];
                    $changed_date = implode('-', $changed_date);
                    
                    $tempDate = date('Y-m', strtotime("+1 month", strtotime($changed_date)));
                    
                    $dateWithLastDayOfMonth = date('Y-m-t', strtotime($tempDate));
                    
                    $lastDayOfMonth = explode('-', $dateWithLastDayOfMonth)[2];
                    
                    if ($lastDayOfMonth<$fixedDayN){
                        
                        $currentDate = $dateWithLastDayOfMonth;
                        $isProcessed = true;
                    } 
                }
                if (!$isProcessed){
                    // for ordinary days, less than 28th, set the start date's fixed day
                    
                    $newDate = date('Y-m-d', strtotime("+1 month", strtotime($currentDate)));
                    
                    $date = explode('-', $newDate);
                    $date[2] = $fixedDay;
                    $date = implode('-', $date);
                    
                    $currentDate = date('Y-m-d', strtotime($date));
                    
                }
                #echo date_format(new DateTime($currentDate),'d-m-Y') . "\n";
                $data['scheduledDates'][] = $currentDate;
                
            }
            
            
            
            // output paymentDate, monthlyPaymentAmount, monthlyInterestAmount, monthlyBasePaymentAmount, amount
            
            $monthlyInterestRate = $yearlyInterestRate/12;
            
            $monthlyPaymentAmount = ($amount*($yearlyInterestRate/(12*100)))/(1-pow(1+$yearlyInterestRate/(12*100), -$months));
            
               
            
            for ($i = 0; $i < $months; $i++){
                $monthlyInterestAmount = $amount*($monthlyInterestRate/100);           
                $data['remainingBaseAmountsBeforePay'][] = $amount;
                if ($i===$months-1){
                    $monthlyBasePaymentAmount = $amount;
                    $amount = $amount - $monthlyBasePaymentAmount;
                    $monthlyPaymentAmount = $monthlyInterestAmount + $monthlyBasePaymentAmount;
                    
                } else {
                    $monthlyBasePaymentAmount = $monthlyPaymentAmount - $monthlyInterestAmount;
                    $amount = $amount - $monthlyBasePaymentAmount;
                }
                $data['monthlyPaymentAmountsForPay'][] = $monthlyPaymentAmount;
                $data['monthlyInterestAmountsForPay'][] = $monthlyInterestAmount;
                $data['monthlyBasePaymentAmountsForPay'][] = $monthlyBasePaymentAmount;
                $data['remainingBaseAmountsAfterPay'][] = $amount;
                
            }
            
            
            return $data;
        }
        
        /**
         * @Route("/loan/{id}", name="loan_show")
         * @Method({"GET"})
         */
        public function show($id){            
            $loan = $this->getDoctrine()->getRepository(Loan::class)->find($id);
            
            $payments = $loan->getPayments()->getValues();

            
            return $this->render('loans/show.html.twig', array(
                                    'loan'=> $loan, 
                                    'payments' => $payments

                            ));

        }

        /**
         * @Route("/loan/delete/{id}")
         * @Method({"DELETE"})
         */
        public function delete(Request $request, $id){
            $loan = $this->getDoctrine()->getRepository(Loan::class)->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($loan);
            $entityManager->flush();

            $response = new Response();
            $response->send();
        }

        
        
        
        // /**
        //  * @Route("/article/save")
        //  */
        // public function save(){
        //     $entityManager = $this->getDoctrine()->getManager();
            
        //     $article = new Article();
        //     $article->setTitle('Test article');
        //     $article->setBody('kshfdkjsfjsk jsdfhkjs');

        //     $entityManager->persist($article);
            
        //     $entityManager->flush();

        //     return new Response('Saved an article with the id of ' . $article->getId());
        // }
    }