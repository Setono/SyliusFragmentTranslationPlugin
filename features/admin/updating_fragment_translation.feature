@managing_fragment_translations
Feature: Updating a fragment translation
  In order to change fragment translation details
  As an Administrator
  I want to be able to edit a fragment translation

  Background:
    Given the store operates on a single channel in "United States"
    And the store has locale "da_DK"
    Given the store has a fragment translation with locale "da_DK", search "search", and replacement "replacement"
    And I am logged in as an administrator

  @ui
  Scenario: Updating fragment translation search string
    Given I want to update the fragment translation with locale "da_DK", search "search", and replacement "replacement"
    When I update the fragment translation with search "search2"
    And I save my changes
    Then I should be notified that it has been successfully edited
    And this fragment translation's search string should be "search2"
