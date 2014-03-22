json.array!(@standard_availabilities) do |standard_availability|
  json.extract! standard_availability, :id, :name, :monday_start, :monday_end, :tuesday_start, :tuesday_end, :wednesday_start, :wednesday_end, :thursday_start, :thursday_end, :friday_start, :friday_end, :saturday_start, :saturday_end, :sunday_start, :sunday_end
  json.url standard_availability_url(standard_availability, format: :json)
end
