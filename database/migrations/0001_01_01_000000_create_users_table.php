<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            // اطلاعات هویتی پایه
            $table->string('first_name');                        // نام
            $table->string('middle_name')->nullable();           // نام میانی یا اسم دوم
            $table->string('last_name');                         // نام خانوادگی
            $table->string('preferred_name')->nullable();        // نام مستعار یا ترجیحی
            $table->string('national_id', 20)->unique()->nullable();          // کد ملی
            $table->string('birth_certificate_number', 50)->nullable();       // شماره شناسنامه
            $table->date('birth_date')->nullable();                             // تاریخ تولد
            $table->string('birth_place')->nullable();                          // محل تولد

            // تابعیت و وضعیت ملیتی
            $table->string('nationality')->nullable();                          // ملیت اصلی
            $table->string('second_nationality')->nullable();                   // ملیت دوم (در صورت وجود)
            $table->enum('citizenship_status', ['citizen', 'resident', 'refugee', 'stateless'])->nullable(); // وضعیت تابعیت
            $table->string('passport_number', 50)->nullable();                  // شماره گذرنامه

            // وضعیت خانوادگی، تحصیلی و شغلی
            $table->enum('gender', ['male', 'female', 'other'])->nullable();    // جنسیت
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable(); // وضعیت تأهل
            $table->string('father_name')->nullable();                          // نام پدر
            $table->string('mother_name')->nullable();                          // نام مادر
            $table->string('spouse_name')->nullable();                          // نام همسر
            $table->string('education_level')->nullable();                      // سطح تحصیلات
            $table->string('occupation')->nullable();                           // شغل
            $table->string('employer')->nullable();                             // محل اشتغال یا کارفرما
            $table->enum('military_service_status', ['completed', 'exempted', 'serving', 'not_applicable'])->nullable(); // وضعیت خدمت وظیفه

            // اطلاعات پزشکی یا قضایی مؤثر در پرونده
            $table->string('blood_type', 3)->nullable();                        // گروه خونی
            $table->enum('disability_status', ['none', 'physical', 'mental', 'multiple'])->nullable(); // وضعیت معلولیت
            $table->string('disability_description')->nullable();              // توضیح درباره معلولیت
            $table->json('languages')->nullable();                              // زبان‌های مسلط

            // وضعیت حیات و سوابق مرتبط
            $table->enum('status', ['active', 'inactive', 'deceased'])->default('active'); // وضعیت کلی فرد
            $table->date('death_date')->nullable();                             // تاریخ فوت
            $table->string('death_place')->nullable();                          // محل فوت
            $table->string('death_certificate_number', 50)->nullable();         // شماره گواهی فوت

            // فایل‌ها و داده‌های تکمیلی
            $table->string('avatar_path')->nullable();                          // مسیر تصویر یا آواتار
            $table->json('biometric_meta')->nullable();                         // اطلاعات بیومتریک (اثر انگشت، چهره و غیره)
            $table->json('extra_attributes')->nullable();                       // سایر خصوصیات تکمیلی

            $table->timestamps();
});
        Schema::create('person_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->constrained('persons')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('child_id')
                ->constrained('persons')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->enum('relation_type', ['father', 'mother', 'guardian', 'custom'])->default('father');
            $table->string('custom_label')->nullable(); // برای relation_type = custom
            $table->timestamps();

            $table->unique(['parent_id', 'child_id', 'relation_type'], 'person_relationship_unique');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')
                ->constrained('persons')
                ->cascadeOnUpdate()
                ->restrictOnDelete(); // حذف کاربر باعث حذف شخص نشود

            $table->string('username')->unique()->nullable(); // در صورت نیاز
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->json('meta')->nullable();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('organizations')
                ->nullOnDelete();

            $table->timestamps();
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label')->nullable();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('roles')
                ->nullOnDelete();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->timestamps();
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->unique(['role_id', 'ended_at'], 'uq_role_active_owner');
            $table->primary(['role_id', 'user_id']);
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label')->nullable();
            $table->string('module')->nullable();
            $table->timestamps();
        });
        Schema::create('permissionables', function (Blueprint $table) {
            $table->foreignId('permission_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('permissionable_id');
            $table->string('permissionable_type');

            $table->index(['permissionable_id', 'permissionable_type'], 'permissionable_index');
            $table->unique(['permission_id', 'permissionable_id', 'permissionable_type'], 'permissionable_unique');
        });
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('person_id')
                ->constrained('persons')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->enum('contact_type', [
                'mobile',
                'phone',
                'email',
                'fax',
                'social',
                'address',
                'website',
                'emergency',
                'custom',
            ])->default('mobile');

            $table->string('label')->nullable();        // مثال: منزل، محل کار، واتس‌اپ
            $table->string('value');                    // مقدار تماس (شماره، ایمیل، آدرس…)
            $table->boolean('is_primary')->default(false);
            $table->json('meta')->nullable();           // اطلاعات تکمیلی (کشور، داخلی، موقعیت، یادداشت و …)

            $table->timestamps();

            $table->index(['person_id', 'contact_type', 'is_primary'], 'person_contacts_lookup_idx');
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('title')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['number'], 'documents_type_creator_idx');
        });

        Schema::create('documentables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                ->constrained('documents')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('documentable_id');
            $table->string('documentable_type');

            $table->string('context')->nullable(); // مثلا: evidence, attachment, contract
            $table->string('checksum', 128)->nullable();

            $table->timestamps();

            $table->unique(
                ['document_id', 'documentable_id', 'documentable_type', 'context'],
                'documentables_unique'
            );

            $table->index(['documentable_id', 'documentable_type'], 'documentables_morph_idx');
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('documentables');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('permissionables');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('person_relationships');
        Schema::dropIfExists('persons');
    }
};
