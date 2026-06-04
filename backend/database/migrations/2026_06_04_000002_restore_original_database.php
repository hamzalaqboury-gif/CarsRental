<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Clear existing seeded data
        DB::table('activity_logs')->truncate();
        DB::table('payments')->truncate();
        DB::table('reservations')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('vehicles')->truncate();
        DB::table('users')->truncate();

        // ── Users ────────────────────────────────────────────────────────────
        DB::table('users')->insert([
            ['id'=>1,'name'=>'Super Admin','email'=>'superadmin@carsrental.com','phone'=>'+1234567890','address'=>null,'driver_license'=>null,'avatar'=>null,'is_active'=>1,'email_verified_at'=>null,'password'=>'$2y$12$zkyj0dEeCXs/hE1f9Yza/ub.1S.ZfH4gTn2eMi0hdeLs2LetRaU5W','remember_token'=>null,'created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03','deleted_at'=>null],
            ['id'=>2,'name'=>'Admin User','email'=>'admin@carsrental.com','phone'=>'+1234567891','address'=>null,'driver_license'=>null,'avatar'=>null,'is_active'=>1,'email_verified_at'=>null,'password'=>'$2y$12$Gd5F8aHms1LjRLLylLPIn.pF.LEpBn9YFVhr84dZHuA4iU2dVoZF.','remember_token'=>null,'created_at'=>'2026-05-13 20:54:04','updated_at'=>'2026-05-13 21:27:54','deleted_at'=>null],
            ['id'=>3,'name'=>'Fleet Manager','email'=>'manager@carsrental.com','phone'=>'+1234567892','address'=>null,'driver_license'=>null,'avatar'=>null,'is_active'=>1,'email_verified_at'=>null,'password'=>'$2y$12$wercKOl.sgPr.9igvsJ99.EaEtHcadVVhZRAwZyu9bb1KRMOq1H8e','remember_token'=>null,'created_at'=>'2026-05-13 20:54:04','updated_at'=>'2026-05-13 20:54:04','deleted_at'=>null],
            ['id'=>4,'name'=>'John Client','email'=>'client@carsrental.com','phone'=>'+1234567893','address'=>'123 Main St, New York, NY','driver_license'=>null,'avatar'=>null,'is_active'=>1,'email_verified_at'=>null,'password'=>'$2y$12$sgMxprYpfC3WP/gV6kzsIeQZPHwAM3NzeMzA8hGdkNrnHG7zoKGci','remember_token'=>null,'created_at'=>'2026-05-13 20:54:04','updated_at'=>'2026-05-13 20:54:04','deleted_at'=>null],
            ['id'=>5,'name'=>'Anas','email'=>'Anas@gmail.com','phone'=>'0762762887','address'=>null,'driver_license'=>null,'avatar'=>null,'is_active'=>0,'email_verified_at'=>null,'password'=>'$2y$12$PVwBkI9PmiVDBXgqD3KIf.AcBMVrWNSMQHkDnCvp5ynWYalAdJclO','remember_token'=>null,'created_at'=>'2026-05-13 21:28:10','updated_at'=>'2026-05-16 13:43:09','deleted_at'=>null],
        ]);
        DB::statement("ALTER TABLE users AUTO_INCREMENT = 6");

        // ── Roles & Permissions ───────────────────────────────────────────────
        DB::table('roles')->insert([
            ['id'=>1,'name'=>'super-admin','guard_name'=>'api','created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03'],
            ['id'=>2,'name'=>'admin','guard_name'=>'api','created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03'],
            ['id'=>3,'name'=>'manager','guard_name'=>'api','created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03'],
            ['id'=>4,'name'=>'client','guard_name'=>'api','created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03'],
        ]);
        DB::statement("ALTER TABLE roles AUTO_INCREMENT = 5");

        DB::table('permissions')->insert([
            ['id'=>1,'name'=>'manage-users','guard_name'=>'api','created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03'],
            ['id'=>2,'name'=>'manage-vehicles','guard_name'=>'api','created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03'],
            ['id'=>3,'name'=>'manage-reservations','guard_name'=>'api','created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03'],
            ['id'=>4,'name'=>'view-dashboard','guard_name'=>'api','created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03'],
            ['id'=>5,'name'=>'make-reservations','guard_name'=>'api','created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03'],
            ['id'=>6,'name'=>'manage-payments','guard_name'=>'api','created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03'],
            ['id'=>7,'name'=>'view-reports','guard_name'=>'api','created_at'=>'2026-05-13 20:54:03','updated_at'=>'2026-05-13 20:54:03'],
        ]);
        DB::statement("ALTER TABLE permissions AUTO_INCREMENT = 8");

        DB::table('role_has_permissions')->insert([
            ['permission_id'=>1,'role_id'=>1],['permission_id'=>1,'role_id'=>2],
            ['permission_id'=>2,'role_id'=>1],['permission_id'=>2,'role_id'=>2],['permission_id'=>2,'role_id'=>3],
            ['permission_id'=>3,'role_id'=>1],['permission_id'=>3,'role_id'=>2],['permission_id'=>3,'role_id'=>3],
            ['permission_id'=>4,'role_id'=>1],['permission_id'=>4,'role_id'=>2],['permission_id'=>4,'role_id'=>3],['permission_id'=>4,'role_id'=>4],
            ['permission_id'=>5,'role_id'=>1],['permission_id'=>5,'role_id'=>4],
            ['permission_id'=>6,'role_id'=>1],['permission_id'=>6,'role_id'=>2],
            ['permission_id'=>7,'role_id'=>1],['permission_id'=>7,'role_id'=>2],['permission_id'=>7,'role_id'=>3],
        ]);

        DB::table('model_has_roles')->insert([
            ['role_id'=>1,'model_type'=>'App\\Models\\User','model_id'=>1],
            ['role_id'=>2,'model_type'=>'App\\Models\\User','model_id'=>2],
            ['role_id'=>3,'model_type'=>'App\\Models\\User','model_id'=>3],
            ['role_id'=>3,'model_type'=>'App\\Models\\User','model_id'=>5],
            ['role_id'=>4,'model_type'=>'App\\Models\\User','model_id'=>4],
        ]);

        // ── Vehicles (original data + Unsplash images) ────────────────────────
        DB::table('vehicles')->insert([
            ['id'=>1,'brand'=>'Toyota','model'=>'Camry','category'=>'sedan','transmission'=>'automatic','fuel_type'=>'petrol','price_per_day'=>75.00,'description'=>'Comfortable midsize sedan, perfect for business travel.','is_available'=>0,'year'=>2023,'seats'=>5,'color'=>'Silver','license_plate'=>'ABC-1001','image'=>'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800&q=80','created_by'=>2,'deleted_at'=>null,'created_at'=>'2026-05-13 20:54:04','updated_at'=>'2026-05-16 13:41:04'],
            ['id'=>2,'brand'=>'Ford','model'=>'Explorer','category'=>'suv','transmission'=>'automatic','fuel_type'=>'petrol','price_per_day'=>95.00,'description'=>'Spacious SUV ideal for family road trips.','is_available'=>1,'year'=>2023,'seats'=>7,'color'=>'White','license_plate'=>'ABC-1002','image'=>'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800&q=80','created_by'=>2,'deleted_at'=>null,'created_at'=>'2026-05-13 20:54:04','updated_at'=>'2026-05-13 23:22:47'],
            ['id'=>3,'brand'=>'Tesla','model'=>'Model 3','category'=>'sedan','transmission'=>'automatic','fuel_type'=>'electric','price_per_day'=>120.00,'description'=>'Premium electric vehicle with autopilot features.','is_available'=>1,'year'=>2024,'seats'=>5,'color'=>'Red','license_plate'=>'ABC-1003','image'=>'https://images.unsplash.com/photo-1536700503339-1e771d4c14d4?w=800&q=80','created_by'=>2,'deleted_at'=>null,'created_at'=>'2026-05-13 20:54:04','updated_at'=>'2026-05-13 20:54:04'],
            ['id'=>4,'brand'=>'BMW','model'=>'5 Series','category'=>'luxury','transmission'=>'automatic','fuel_type'=>'petrol','price_per_day'=>180.00,'description'=>'Luxury sedan with premium interior and cutting-edge tech.','is_available'=>1,'year'=>2024,'seats'=>5,'color'=>'Black','license_plate'=>'ABC-1004','image'=>'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&q=80','created_by'=>2,'deleted_at'=>null,'created_at'=>'2026-05-13 20:54:04','updated_at'=>'2026-05-13 20:54:04'],
            ['id'=>5,'brand'=>'Chevrolet','model'=>'Silverado','category'=>'truck','transmission'=>'automatic','fuel_type'=>'diesel','price_per_day'=>110.00,'description'=>'Heavy-duty pickup truck for work and adventure.','is_available'=>1,'year'=>2022,'seats'=>5,'color'=>'Blue','license_plate'=>'ABC-1005','image'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80','created_by'=>2,'deleted_at'=>null,'created_at'=>'2026-05-13 20:54:04','updated_at'=>'2026-05-13 20:54:04'],
            ['id'=>6,'brand'=>'Honda','model'=>'Civic','category'=>'economy','transmission'=>'manual','fuel_type'=>'petrol','price_per_day'=>45.00,'description'=>'Fuel-efficient compact car, great value for money.','is_available'=>1,'year'=>2022,'seats'=>5,'color'=>'Gray','license_plate'=>'ABC-1006','image'=>'https://images.unsplash.com/photo-1606611013016-969c19ba27bb?w=800&q=80','created_by'=>2,'deleted_at'=>null,'created_at'=>'2026-05-13 20:54:04','updated_at'=>'2026-05-13 20:54:04'],
            ['id'=>7,'brand'=>'Mercedes-Benz','model'=>'Sprinter','category'=>'van','transmission'=>'automatic','fuel_type'=>'diesel','price_per_day'=>130.00,'description'=>'Large cargo/passenger van with ample space.','is_available'=>1,'year'=>2023,'seats'=>9,'color'=>'White','license_plate'=>'ABC-1007','image'=>'https://images.unsplash.com/photo-1559416523-140ddc3d238c?w=800&q=80','created_by'=>2,'deleted_at'=>null,'created_at'=>'2026-05-13 20:54:04','updated_at'=>'2026-05-13 20:54:04'],
            ['id'=>8,'brand'=>'Porsche','model'=>'Cayenne','category'=>'luxury','transmission'=>'automatic','fuel_type'=>'hybrid','price_per_day'=>250.00,'description'=>'Ultra-luxury sports SUV with hybrid powertrain.','is_available'=>1,'year'=>2024,'seats'=>5,'color'=>'Midnight Blue','license_plate'=>'ABC-1008','image'=>'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80','created_by'=>2,'deleted_at'=>null,'created_at'=>'2026-05-13 20:54:04','updated_at'=>'2026-05-13 20:54:04'],
        ]);
        DB::statement("ALTER TABLE vehicles AUTO_INCREMENT = 9");

        // ── Reservations ──────────────────────────────────────────────────────
        DB::table('reservations')->insert([
            ['id'=>1,'user_id'=>4,'vehicle_id'=>6,'start_date'=>'2026-05-18','end_date'=>'2026-05-28','total_days'=>10,'total_price'=>450.00,'status'=>'paid','payment_status'=>'paid','pickup_location'=>null,'return_location'=>null,'notes'=>null,'confirmed_by'=>null,'confirmed_at'=>null,'cancelled_at'=>null,'cancellation_reason'=>null,'deleted_at'=>null,'created_at'=>'2026-05-13 20:59:23','updated_at'=>'2026-05-13 20:59:25'],
            ['id'=>2,'user_id'=>4,'vehicle_id'=>3,'start_date'=>'2026-05-13','end_date'=>'2026-05-17','total_days'=>4,'total_price'=>480.00,'status'=>'paid','payment_status'=>'paid','pickup_location'=>null,'return_location'=>null,'notes'=>null,'confirmed_by'=>null,'confirmed_at'=>null,'cancelled_at'=>null,'cancellation_reason'=>null,'deleted_at'=>null,'created_at'=>'2026-05-13 21:16:51','updated_at'=>'2026-05-13 21:17:07'],
            ['id'=>3,'user_id'=>4,'vehicle_id'=>2,'start_date'=>'2026-05-14','end_date'=>'2026-05-15','total_days'=>1,'total_price'=>95.00,'status'=>'paid','payment_status'=>'paid','pickup_location'=>null,'return_location'=>null,'notes'=>null,'confirmed_by'=>null,'confirmed_at'=>null,'cancelled_at'=>null,'cancellation_reason'=>null,'deleted_at'=>null,'created_at'=>'2026-05-13 21:22:25','updated_at'=>'2026-05-13 21:22:46'],
            ['id'=>4,'user_id'=>4,'vehicle_id'=>4,'start_date'=>'2026-05-13','end_date'=>'2026-05-14','total_days'=>1,'total_price'=>180.00,'status'=>'paid','payment_status'=>'paid','pickup_location'=>null,'return_location'=>null,'notes'=>null,'confirmed_by'=>null,'confirmed_at'=>null,'cancelled_at'=>null,'cancellation_reason'=>null,'deleted_at'=>null,'created_at'=>'2026-05-13 21:40:33','updated_at'=>'2026-05-13 21:41:06'],
            ['id'=>5,'user_id'=>4,'vehicle_id'=>2,'start_date'=>'2026-05-16','end_date'=>'2026-05-17','total_days'=>1,'total_price'=>95.00,'status'=>'cancelled','payment_status'=>'unpaid','pickup_location'=>null,'return_location'=>null,'notes'=>null,'confirmed_by'=>null,'confirmed_at'=>null,'cancelled_at'=>'2026-05-13 21:42:01','cancellation_reason'=>'Client requested cancellation','deleted_at'=>null,'created_at'=>'2026-05-13 21:41:47','updated_at'=>'2026-05-13 21:42:01'],
            ['id'=>6,'user_id'=>4,'vehicle_id'=>7,'start_date'=>'2026-05-14','end_date'=>'2026-05-15','total_days'=>1,'total_price'=>130.00,'status'=>'cancelled','payment_status'=>'unpaid','pickup_location'=>null,'return_location'=>null,'notes'=>null,'confirmed_by'=>null,'confirmed_at'=>null,'cancelled_at'=>'2026-05-16 13:37:16','cancellation_reason'=>'Client requested cancellation','deleted_at'=>null,'created_at'=>'2026-05-13 23:26:32','updated_at'=>'2026-05-16 13:37:16'],
            ['id'=>7,'user_id'=>4,'vehicle_id'=>1,'start_date'=>'2026-05-28','end_date'=>'2026-05-31','total_days'=>3,'total_price'=>225.00,'status'=>'pending','payment_status'=>'unpaid','pickup_location'=>null,'return_location'=>null,'notes'=>null,'confirmed_by'=>null,'confirmed_at'=>null,'cancelled_at'=>null,'cancellation_reason'=>null,'deleted_at'=>null,'created_at'=>'2026-05-16 13:37:02','updated_at'=>'2026-05-16 13:37:02'],
        ]);
        DB::statement("ALTER TABLE reservations AUTO_INCREMENT = 8");

        // ── Payments ──────────────────────────────────────────────────────────
        DB::table('payments')->insert([
            ['id'=>1,'reservation_id'=>1,'user_id'=>4,'amount'=>450.00,'method'=>'mock','status'=>'completed','transaction_id'=>'MOCK-WVFHNTWRFYPM1WN9','paypal_order_id'=>null,'paypal_payer_id'=>null,'gateway_response'=>'{"mock":true,"auto_approved":true}','paid_at'=>'2026-05-13 20:59:25','created_at'=>'2026-05-13 20:59:25','updated_at'=>'2026-05-13 20:59:25'],
            ['id'=>2,'reservation_id'=>2,'user_id'=>4,'amount'=>480.00,'method'=>'mock','status'=>'completed','transaction_id'=>'MOCK-40BKSP5CI6GYBGW9','paypal_order_id'=>null,'paypal_payer_id'=>null,'gateway_response'=>'{"mock":true,"auto_approved":true}','paid_at'=>'2026-05-13 21:17:07','created_at'=>'2026-05-13 21:17:07','updated_at'=>'2026-05-13 21:17:07'],
            ['id'=>3,'reservation_id'=>3,'user_id'=>4,'amount'=>95.00,'method'=>'mock','status'=>'completed','transaction_id'=>'MOCK-9DY6EM781AL5SSUN','paypal_order_id'=>null,'paypal_payer_id'=>null,'gateway_response'=>'{"mock":true,"auto_approved":true}','paid_at'=>'2026-05-13 21:22:46','created_at'=>'2026-05-13 21:22:46','updated_at'=>'2026-05-13 21:22:46'],
            ['id'=>4,'reservation_id'=>4,'user_id'=>4,'amount'=>180.00,'method'=>'mock','status'=>'completed','transaction_id'=>'MOCK-PCTH7JMJD6UB85EQ','paypal_order_id'=>null,'paypal_payer_id'=>null,'gateway_response'=>'{"mock":true,"auto_approved":true}','paid_at'=>'2026-05-13 21:41:06','created_at'=>'2026-05-13 21:41:06','updated_at'=>'2026-05-13 21:41:06'],
        ]);
        DB::statement("ALTER TABLE payments AUTO_INCREMENT = 5");

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // Intentionally left empty — this migration restores data, not schema
    }
};
