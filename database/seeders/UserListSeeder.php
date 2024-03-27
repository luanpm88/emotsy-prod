<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \Acelle\Model\User::where('email', '=', 'anna@emotsy.com')->first();
        $customer = $user->customer;
        $listName = '#UserList';
        $list = \Acelle\Model\MailList::where('name', '=', $listName)->first();

        if (!$list) {
            $contact = $contact = \Acelle\Model\Contact::create([
                'company' => 'Emosty',
            ]);

            $list = new \Acelle\Model\MailList();
            $list->name = $listName;
            $list->from_email = 'info@emotsy.com';
            $list->from_name = 'Emotsy';
            $list->subscribe_confirmation = false;
            $list->send_welcome_email = false;
            $list->unsubscribe_notification = false;
            $list->customer_id = $customer->id;
            $list->contact_id = $contact->id;
            $list->save();
        }

        foreach(\Acelle\Model\User::all() as $user) {
            $subscriber = $list->subscribers()->firstOrNew(['email' => strtolower(trim($user->email))]);
            $subscriber->from = \Acelle\Model\Subscriber::SUBSCRIPTION_TYPE_ADDED;
            $subscriber->status = 'subscribed';
            $subscriber->save();

            // fields
            $subscriber->updateFields([
                'EMAIL' => $user->email,
                'FIRST_NAME' => $user->first_name,
                'LAST_NAME' => $user->last_name,
            ]);

            var_dump($subscriber);
        }
    }
}
