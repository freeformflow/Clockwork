class StandardAvailabilitiesController < ApplicationController
  before_action :set_standard_availability, only: [:show, :edit, :update, :destroy]

  # GET /standard_availabilities
  # GET /standard_availabilities.json
  def index
    @standard_availabilities = StandardAvailability.all
  end

  # GET /standard_availabilities/1
  # GET /standard_availabilities/1.json
  def show
  end

  # GET /standard_availabilities/new
  def new
    @standard_availability = StandardAvailability.new
  end

  # GET /standard_availabilities/1/edit
  def edit
  end

  # POST /standard_availabilities
  # POST /standard_availabilities.json
  def create
    @standard_availability = StandardAvailability.new(standard_availability_params)

    respond_to do |format|
      if @standard_availability.save
        format.html { redirect_to @standard_availability, notice: 'Standard availability was successfully created.' }
        format.json { render action: 'show', status: :created, location: @standard_availability }
      else
        format.html { render action: 'new' }
        format.json { render json: @standard_availability.errors, status: :unprocessable_entity }
      end
    end
  end

  # PATCH/PUT /standard_availabilities/1
  # PATCH/PUT /standard_availabilities/1.json
  def update
    respond_to do |format|
      if @standard_availability.update(standard_availability_params)
        format.html { redirect_to @standard_availability, notice: 'Standard availability was successfully updated.' }
        format.json { head :no_content }
      else
        format.html { render action: 'edit' }
        format.json { render json: @standard_availability.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /standard_availabilities/1
  # DELETE /standard_availabilities/1.json
  def destroy
    @standard_availability.destroy
    respond_to do |format|
      format.html { redirect_to standard_availabilities_url }
      format.json { head :no_content }
    end
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_standard_availability
      @standard_availability = StandardAvailability.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def standard_availability_params
      params.require(:standard_availability).permit(:name, :monday_start, :monday_end, :tuesday_start, :tuesday_end, :wednesday_start, :wednesday_end, :thursday_start, :thursday_end, :friday_start, :friday_end, :saturday_start, :saturday_end, :sunday_start, :sunday_end)
    end
end
