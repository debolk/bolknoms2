<?php

class DeregisterController extends ApplicationController
{
    /**
    * Removes a registration for a meal
    * @return Redirect
    */
    public function afmelden($id, $salt)
    {
        // Find the registration
        $registration = Registration::find($id);
        if (!$registration) {
            Flash::set(Flash::ERROR, 'We kunnen je niet afmelden voor deze maaltijd, want je bent niet aangemeld.  Misschien ben je eerder al afgemeld.');
            return Redirect::to('/');
        }

        // Check if the salt is valid
        if ($registration->salt !== $salt) {
            Flash::set(Flash::ERROR, 'De beveiligingscode klopt niet. Je bent niet afgemeld.');
            return Redirect::to('/');
        }

        // Check if the subscription period has not ended yet
        if (! $registration->meal->open_for_registrations()) {
            Flash::set(Flash::ERROR, 'De inschrijving voor deze maaltijd is gesloten. Je kunt je niet meer afmelden.');
            return Redirect::to('/');
        }

        // Store variables for later usage
        $date = (string)$registration->meal;
        $id   = $registration->id;
        $name = $registration->name;
        $meal = $registration->meal->date;

        // Remove registration
        $registration->delete();
        Log::info("Afgemeld: e-mail|$id|$name|$meal");

        // Notify the user
        Flash::set(Flash::SUCCESS, "Je bent afgemeld voor de maaltijd op $date");
        return Redirect::to('/');
    }
}
