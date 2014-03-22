# encoding: UTF-8
# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended that you check this file into your version control system.

ActiveRecord::Schema.define(version: 20140322020326) do

  create_table "people", force: true do |t|
    t.string   "last_name"
    t.string   "first_name"
    t.string   "email_address"
    t.string   "phone_number"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "standard_availabilities", force: true do |t|
    t.string   "name"
    t.integer  "monday_start"
    t.integer  "monday_end"
    t.integer  "tuesday_start"
    t.integer  "tuesday_end"
    t.integer  "wednesday_start"
    t.integer  "wednesday_end"
    t.integer  "thursday_start"
    t.integer  "thursday_end"
    t.integer  "friday_start"
    t.integer  "friday_end"
    t.integer  "saturday_start"
    t.integer  "saturday_end"
    t.integer  "sunday_start"
    t.integer  "sunday_end"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

end
