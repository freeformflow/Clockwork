class DropStandardAvailabilitiesTable < ActiveRecord::Migration
  def change
     drop_table :standard_availabilities
  end
end
