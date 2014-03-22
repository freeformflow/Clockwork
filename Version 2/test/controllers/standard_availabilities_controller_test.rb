require 'test_helper'

class StandardAvailabilitiesControllerTest < ActionController::TestCase
  setup do
    @standard_availability = standard_availabilities(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:standard_availabilities)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create standard_availability" do
    assert_difference('StandardAvailability.count') do
      post :create, standard_availability: { friday_end: @standard_availability.friday_end, friday_start: @standard_availability.friday_start, monday_end: @standard_availability.monday_end, monday_start: @standard_availability.monday_start, name: @standard_availability.name, saturday_end: @standard_availability.saturday_end, saturday_start: @standard_availability.saturday_start, sunday_end: @standard_availability.sunday_end, sunday_start: @standard_availability.sunday_start, thursday_end: @standard_availability.thursday_end, thursday_start: @standard_availability.thursday_start, tuesday_end: @standard_availability.tuesday_end, tuesday_start: @standard_availability.tuesday_start, wednesday_end: @standard_availability.wednesday_end, wednesday_start: @standard_availability.wednesday_start }
    end

    assert_redirected_to standard_availability_path(assigns(:standard_availability))
  end

  test "should show standard_availability" do
    get :show, id: @standard_availability
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @standard_availability
    assert_response :success
  end

  test "should update standard_availability" do
    patch :update, id: @standard_availability, standard_availability: { friday_end: @standard_availability.friday_end, friday_start: @standard_availability.friday_start, monday_end: @standard_availability.monday_end, monday_start: @standard_availability.monday_start, name: @standard_availability.name, saturday_end: @standard_availability.saturday_end, saturday_start: @standard_availability.saturday_start, sunday_end: @standard_availability.sunday_end, sunday_start: @standard_availability.sunday_start, thursday_end: @standard_availability.thursday_end, thursday_start: @standard_availability.thursday_start, tuesday_end: @standard_availability.tuesday_end, tuesday_start: @standard_availability.tuesday_start, wednesday_end: @standard_availability.wednesday_end, wednesday_start: @standard_availability.wednesday_start }
    assert_redirected_to standard_availability_path(assigns(:standard_availability))
  end

  test "should destroy standard_availability" do
    assert_difference('StandardAvailability.count', -1) do
      delete :destroy, id: @standard_availability
    end

    assert_redirected_to standard_availabilities_path
  end
end
