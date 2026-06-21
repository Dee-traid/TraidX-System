<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateInitialTraidxSchema extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $users = $this->table('users', ['id' => false, 'primary_key' => 'id']);
        $users->addColumn('id', 'biginteger', ['identity' => true])
              ->addColumn('username', 'string', ['limit' => 255])
              ->addColumn('email', 'string', ['limit' => 191])
              ->addColumn('password', 'string', ['limit' => 255])
              ->addColumn('phone', 'string', ['limit' => 20, 'null' => true])
              ->addColumn('role', 'string', ['limit' => 20, 'default' => 'user'])
              ->addColumn('status', 'string', ['limit' => 20, 'default' => 'active'])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
              ->addIndex(['email'], ['unique' => true])
              ->addIndex(['username'], ['unique' => true])
              ->create();

        $this->execute("ALTER TABLE users ADD CONSTRAINT check_user_role CHECK (role IN ('user', 'admin'));");
        $this->execute("ALTER TABLE users ADD CONSTRAINT check_user_status CHECK (status IN ('active', 'inactive'));");

        // Airline table
        $airlines = $this->table('airlines', ['id' => false, 'primary_key' => 'id']);
        $airlines->addColumn('id', 'integer', ['identity' => true])
                 ->addColumn('name', 'string', ['limit' => 225])
                 ->addColumn('code', 'char', ['limit' => 3])
                 ->addColumn('logo_url', 'string', ['limit' => 300, 'null' => true])
                 ->addColumn('status', 'string', ['limit' => 20, 'default' => 'active'])

                 ->create();

        $this->execute("ALTER TABLE airlines ADD CONSTRAINT check_airline_status CHECK (status IN ('active', 'inactive'));");

        // Airports table
        $airports = $this->table('airports', ['id' => false, 'primary_key' => 'id']);
        $airports->addColumn('id', 'integer', ['identity' => true])
                 ->addColumn('name', 'string', ['limit' => 200])
                 ->addColumn('code', 'char', ['limit' => 3])
                 ->addColumn('city', 'string', ['limit' => 100])
                 ->addColumn('country', 'string', ['limit' => 100])
                 ->addColumn('timezone', 'string', ['limit' => 60])
                 ->addIndex(['code'], ['unique' => true])
                 ->create();

                // Flights table
        $flights = $this->table('flights', ['id' => false, 'primary_key' => 'id']);
        $flights->addColumn('id', 'biginteger', ['identity' => true])
                ->addColumn('airline_id', 'integer')
                ->addColumn('flight_number', 'string', ['limit' => 20])
                ->addColumn('origin_id', 'integer')
                ->addColumn('destination_id', 'integer')
                ->addColumn('departure_time', 'timestamp')
                ->addColumn('arrival_time', 'timestamp')
                ->addColumn('base_fare', 'decimal', ['precision' => 10, 'scale' => 2])
                ->addColumn('tax_rate', 'decimal', ['precision' => 5, 'scale' => 4, 'default' => 0.0750])
                ->addColumn('total_seats', 'smallinteger')
                ->addColumn('available_seats', 'smallinteger')
                ->addColumn('status', 'string', ['limit' => 20, 'default' => 'scheduled'])
                ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                
                // Foreign Keys
                ->addForeignKey('airline_id', 'airlines', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
                ->addForeignKey('origin_id', 'airports', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
                ->addForeignKey('destination_id', 'airports', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
                ->create();

        $this->execute("ALTER TABLE flights ADD CONSTRAINT check_flight_status CHECK (status IN ('scheduled','delayed','cancelled','completed'));");

        // Seats table
        $seats = $this->table('seats', ['id' => false, 'primary_key' => 'id']);
        $seats->addColumn('id', 'biginteger', ['identity' => true])
              ->addColumn('flight_id', 'biginteger')
              ->addColumn('seat_number', 'string', ['limit' => 5])
              ->addColumn('class', 'string', ['limit' => 20])
              ->addColumn('is_available', 'boolean', ['default' => true])
              ->addColumn('price_modifier', 'decimal', ['precision' => 5, 'scale' => 4, 'default' => 1.0000])
              
              ->addForeignKey('flight_id', 'flights', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
              ->addIndex(['flight_id', 'seat_number'], ['unique' => true])
              ->create();

        $this->execute("ALTER TABLE seats ADD CONSTRAINT check_seat_class CHECK (class IN ('economy','business','first'));");

        // Bookings table
        $bookings = $this->table('bookings', ['id' => false, 'primary_key' => 'id']);
        $bookings->addColumn('id', 'biginteger', ['identity' => true])
                 ->addColumn('user_id', 'biginteger')
                 ->addColumn('flight_id', 'biginteger')
                 ->addColumn('reference', 'string', ['limit' => 12])
                 ->addColumn('status', 'string', ['limit' => 20, 'default' => 'pending'])
                 ->addColumn('total_amount', 'decimal', ['precision' => 10, 'scale' => 2])
                 ->addColumn('base_amount', 'decimal', ['precision' => 10, 'scale' => 2])
                 ->addColumn('tax_amount', 'decimal', ['precision' => 10, 'scale' => 2])
                 ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                 ->addColumn('cancelled_at', 'timestamp', ['null' => true])
                 
                 // Foreign Keys
                 ->addForeignKey('user_id', 'users', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
                 ->addForeignKey('flight_id', 'flights', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
                 ->addIndex(['reference'], ['unique' => true])
                 ->create();

        // Passengers table
        $passengers = $this->table('passengers', ['id' => false, 'primary_key' => 'id']);
        $passengers->addColumn('id', 'biginteger', ['identity' => true])
                   ->addColumn('booking_id', 'biginteger')
                   ->addColumn('seat_id', 'biginteger')
                   ->addColumn('first_name', 'string', ['limit' => 100])
                   ->addColumn('last_name', 'string', ['limit' => 100])
                   ->addColumn('dob', 'date')
                   ->addColumn('passport_no', 'string', ['limit' => 30, 'null' => true])
                   ->addColumn('nationality', 'string', ['limit' => 60, 'null' => true])
                   
                   // Foreign Keys
                   ->addForeignKey('booking_id', 'bookings', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
                   ->addForeignKey('seat_id', 'seats', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
                   ->create();

        // Notifications table
        $notifications = $this->table('notifications', ['id' => false, 'primary_key' => 'id']);
        $notifications->addColumn('id', 'biginteger', ['identity' => true])
                      ->addColumn('user_id', 'biginteger')
                      ->addColumn('booking_id', 'biginteger', ['null' => true])
                      ->addColumn('type', 'string', ['limit' => 20])
                      ->addColumn('channel', 'string', ['limit' => 20])
                      ->addColumn('title', 'string', ['limit' => 200])
                      ->addColumn('message', 'text')
                      ->addColumn('is_read', 'boolean', ['default' => false])
                      ->addColumn('sent_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                      
                      // Foreign Keys
                      ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
                      ->addForeignKey('booking_id', 'bookings', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
                      ->create();

        $this->execute("ALTER TABLE notifications ADD CONSTRAINT check_notification_type CHECK (type IN ('confirmation','status_update','reminder','refund'));");
        $this->execute("ALTER TABLE notifications ADD CONSTRAINT check_notification_channel CHECK (channel IN ('email','in_app','sms'));");

        // Payments table
        $payments = $this->table('payments', ['id' => false, 'primary_key' => 'id']);
        $payments->addColumn('id', 'biginteger', ['identity' => true])
                 ->addColumn('booking_id', 'biginteger')
                 ->addColumn('method', 'string', ['limit' => 20])
                 ->addColumn('gateway', 'string', ['limit' => 50, 'default' => 'stripe'])
                 ->addColumn('gateway_ref', 'string', ['limit' => 100, 'null' => true])
                 ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2])
                 ->addColumn('currency', 'char', ['limit' => 3, 'default' => 'USD'])
                 ->addColumn('status', 'string', ['limit' => 20, 'default' => 'pending'])
                 ->addColumn('paid_at', 'timestamp', ['null' => true])
                 
                 // Foreign Key
                 ->addForeignKey('booking_id', 'bookings', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
                 ->addIndex(['gateway_ref'], ['unique' => true])
                 ->create();

        $this->execute("ALTER TABLE payments ADD CONSTRAINT check_payment_method CHECK (method IN ('card','mobile_money','bank_transfer'));");
        $this->execute("ALTER TABLE payments ADD CONSTRAINT check_payment_status CHECK (status IN ('pending','success','failed','refunded'));");
    }

    

    
}
