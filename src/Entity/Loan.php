<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoanRepository::class)
 */
class Loan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", name="start_date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $months;

    /**
     * @ORM\Column(type="float", name="yearly_interest_rate")
     */
    private $yearlyInterestRate;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="loan")
     */
    private $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

        
    // Getters & Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function getBody(){
        return $this->body;
    }

    public function setBody($body){
        $this->body = $body;
    }

    public function getStartDate(){
        return $this->startDate;
    }

    public function setStartDate($date){
        $this->startDate = $date;
    }

    public function getAmount(){
        return $this->amount;
    }

    public function setAmount($amount){
        $this->amount = $amount;
    }

    public function getMonths(){
        return $this->months;
    }

    public function setMonths($months){
        $this->months = $months;
    }

    public function getYearlyInterestRate(){
        return $this->yearlyInterestRate;
    }

    public function setYearlyInterestRate($rate){
        $this->yearlyInterestRate = $rate;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setLoan($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->contains($payment)) {
            $this->payments->removeElement($payment);
            // set the owning side to null (unless already changed)
            if ($payment->getLoan() === $this) {
                $payment->setLoan(null);
            }
        }

        return $this;
    }
}
    
