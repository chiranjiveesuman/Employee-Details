<?php

namespace Drupal\ballistic_employee\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class EmployeeDetails extends FormBase {

    public function getFormId() {
        return "employee_details_form";
    }

    /**
     * {@inheritDoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state) {

        $nationalityOptions = array(
            'British' => 'British',
            'China' => 'China',
            'India' => 'India',
            'Other' => 'Other'
        );
        $form['name'] = array(
            '#type' => 'textfield',
            '#title' => 'User Name',
            '#required' => true,
        );
        $form['email'] = array(
            '#type' => 'email',
            '#title' => 'Email',
            '#required' => true,
        );
        $form['phone'] = array(
          '#type' => 'textfield',
          '#title' => 'Your phone number',
          '#maxlength' => 12,
          '#required' => false,
        );
        $form['gender'] = array(
          '#type' => 'radios',
          '#title' => $this->t('Gender'),
          '#required' => true,
          '#options' => ['Male' => $this->t('Male'), 'Female' => $this->t('Female'), 'other' => $this->t('Other')],
        );
        $form['marital_status'] = array(
          '#type' => 'select',
          '#title' => 'Marital Status',
          '#options' => [null => $this->t('None'), 'Married' => $this->t('Married'), 'Unmarried' => $this->t('Unmarried')],
        );
        $form['date_of_birth'] = array(
          '#type' => 'date',
          '#title' => 'Date of Birth',
          '#required' => true,
        );
        $form['about_employee'] = array(
          '#type' => 'textarea',
          '#title' => 'About Employee',
          '#required' => true,
        );
        $form['nationality'] = array(
          '#type' => 'select',
          '#title' => 'Nationality',
          '#options' => $nationalityOptions,
          '#required' => true,
        );
        $form['submit'] = [
          '#type' => 'submit',
          '#value' => 'Submit',
        ];

        return $form;
    }
    public function validateForm(array &$form, FormStateInterface $form_state) {

      $value = $form_state->getValue('email');
      if (!(\Drupal::service('email.validator')->isValid($value))) {
          $form_state->setErrorByName('email', $this->t('It appears the %email is not a valid email. Please try again.', ['%email' => $value]));
      }

    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        \Drupal::messenger()->addMessage("User Details Submitted Successfully");
        $values = $form_state->getValues();
        \Drupal::database()->insert('employee_details')->fields([
            'name' => $values['name'],
            'mail' => $values['email'],
            'phone' => $values['phone'],
            'gender' => $values['gender'],
            'marital_status' => $values['marital_status'],
            'date_of_birth' => $values['date_of_birth'],
            'about_employee' => $values['about_employee'],
            'nationality' => $values['nationality'],
        ])->execute();
        $form_state->setRedirect('employee.getEmployeeList');
    }
}
//dd($values);
