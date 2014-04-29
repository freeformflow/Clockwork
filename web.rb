require 'sinatra'
require 'data_mapper'
require 'json'

# Setup the database for Clockwork.

# This particular table encompases Human Resources and stores everything about employees.
DataMapper::setup(:default, "sqlite3://#{Dir.pwd}/human_resources.db")

class Person

	include DataMapper::Resource
  property :id, Serial
  property :last_name, String
  property :first_name, String
  property :email, String

	property :mon_start, Time
	property :tue_start, Time
	property :wed_start, Time
	property :thu_start, Time
	property :fri_start, Time
	property :sat_start, Time
	property :sun_start, Time

  property :mon_end, Time
  property :tue_end, Time
  property :wed_end, Time
  property :thu_end, Time
  property :fri_end, Time
  property :sat_end, Time
  property :sun_end, Time

end

class Settings

end

DataMapper.finalize.auto_upgrade!


get '/' do

	erb :main_menu

end


set :people_array, ["Matt","John","Sue","Kim","HAL-9000", "Zack", "Kelsey", "Patty", "Marcy", "Jamil", "Courtney"]

get '/views/people.erb' do

	@people = Person.all :order => :last_name.asc 
	erb :people

end

link '/views/people.erb' do
	n = Person.all :order => :last_name.asc
	n.to_json	
end

post '/views/people.erb' do
	x = Person.new

	x.last_name = params[:last_name]
	x.first_name = params[:first_name]
	x.email = params[:email]

	x.save

	n = Person.all :order => :last_name.asc
	n.to_json
end

delete '/views/people.erb' do
	x = Person.get(params[:id])
	x.destroy

	n = Person.all :order => :last_name.asc
	n.to_json
end 


put '/views/people.erb' do
	x = Person.get(params[:id])
	x.last_name = params[:last_name]
	x.first_name = params[:first_name]
	x.email = params[:email]

	x.save
	
	n = Person.all :order => :last_name.asc
	n.to_json
end

get '/views/std_avail.erb' do

  @people = Person.all :order => :last_name.asc
  erb :std_avail

end

