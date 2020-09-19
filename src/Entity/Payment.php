<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", name="scheduled_date")
     */
    private $scheduledDate;
    public function getScheduledDate()
    {
        return $this->scheduledDate;
    }

    public function setScheduledDate($date)
    {
        $this->scheduledDate = \DateTime::createFromFormat ( 'Y-m-d' , $date);
        
    }

    /**
     * @ORM\Column(type="float", name="full_amount_for_pay")
     */
    private $fullAmountForPay;
    public function getFullAmountForPay()
    {
        return $this->fullAmountForPay;
    }

    public function setFullAmountForPay($amount)
    {
        $this->fullAmountForPay = $amount;

    }

    /**
     * @ORM\Column(type="float", name="interest_amount_for_pay")
     */
    private $interestAmountForPay;
    public function getInterestAmountForPay()
    {
        return $this->interestAmountForPay;
    }

    public function setInterestAmountForPay($amount)
    {
        $this->interestAmountForPay = $amount;
        
    }

    /**
     * @ORM\Column(type="float", name="base_amount_for_pay")
     */
    private $baseAmountForPay;
    public function getBaseAmountForPay()
    {
        return $this->baseAmountForPay;
    }

    public function setBaseAmountForPay($amount)
    {
        $this->baseAmountForPay = $amount;
        
    }

    /**
     * @ORM\Column(type="float", name="remaining_base_amount_before_pay")
     */
    private $remainingBaseAmountBeforePay;
    public function getRemainingBaseAmountBeforePay()
    {
        return $this->remainingBaseAmountBeforePay;
    }

    public function setRemainingBaseAmountBeforePay($amount)
    {
        $this->remainingBaseAmountBeforePay = $amount;
        
    }

    /**
     * @ORM\Column(type="float", name="remaining_base_amount_after_pay")
     */
    private $remainingBaseAmountAfterPay;
    public function getRemainingBaseAmountAfterPay()
    {
        return $this->remainingBaseAmountAfterPay;
    }

    public function setRemainingBaseAmountAfterPay($amount)
    {
        $this->remainingBaseAmountAfterPay = $amount;
        
    }

    /**
     * @ORM\ManyToOne(targetEntity=Loan::class, inversedBy="payments")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $loan;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLoan(): ?Loan
    {
        return $this->loan;
    }

    public function setLoan(?Loan $loan): self
    {
        $this->loan = $loan;

        return $this;
    }
}
