class CreateStandardAvailabilities < ActiveRecord::Migration
  def change
    create_table :standard_availabilities do |t|
      t.string :name
      t.integer :monday_start
      t.integer :monday_end
      t.integer :tuesday_start
      t.integer :tuesday_end
      t.integer :wednesday_start
      t.integer :wednesday_end
      t.integer :thursday_start
      t.integer :thursday_end
      t.integer :friday_start
      t.integer :friday_end
      t.integer :saturday_start
      t.integer :saturday_end
      t.integer :sunday_start
      t.integer :sunday_end

      t.timestamps
    end
  end
end
