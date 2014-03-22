class CreatePeople < ActiveRecord::Migration
  def change
    create_table :people do |t|
      t.string :last_name
      t.string :first_name
      t.string :email_address
      t.string :phone_number

      t.timestamps
    end
  end
end
