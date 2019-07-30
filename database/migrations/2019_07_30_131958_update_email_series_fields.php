<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEmailSeriesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('show_affiliate_message', 'show_presented_by');
            $table->renameColumn('affiliate_group_id', 'event_host_id');
        });

        Schema::table('event_programs', function (Blueprint $table) {
            $table->boolean('is_event_host')->nullable()->after('is_affiliate_group');
        });

        Schema::table('email_series', function (Blueprint $table) {
            $table->renameColumn('show_non_member', 'show_nonmember');
            $table->renameColumn('show_affiliate_member', 'show_affiliate');
            $table->renameColumn('non_member_copy', 'nonmember_copy');
            $table->renameColumn('affiliate_member_copy', 'affiliate_copy');
        });

        Schema::table('event_email_series', function (Blueprint $table) {
            $table->renameColumn('send_non_member', 'send_nonmember');
            $table->renameColumn('send_affiliate_member', 'send_affiliate');
            $table->renameColumn('non_member_copy', 'nonmember_copy');
            $table->renameColumn('affiliate_member_copy', 'affiliate_copy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('show_presented_by', 'show_affiliate_message');
            $table->renameColumn('event_host_id', 'affiliate_group_id');
        });

        Schema::table('event_programs', function (Blueprint $table) {
            $table->dropColumn('is_event_host');
        });

        Schema::table('email_series', function (Blueprint $table) {
            $table->renameColumn('show_nonmember', 'show_non_member');
            $table->renameColumn('show_affiliate', 'show_affiliate_member');
            $table->renameColumn('nonmember_copy', 'non_member_copy');
            $table->renameColumn('affiliate_copy', 'affiliate_member_copy');
        });

        Schema::table('event_email_series', function (Blueprint $table) {
            $table->renameColumn('send_nonmember', 'send_non_member');
            $table->renameColumn('send_affiliate', 'send_affiliate_member');
            $table->renameColumn('nonmember_copy', 'non_member_copy');
            $table->renameColumn('affiliate_copy', 'affiliate_member_copy');
        });
    }
}
