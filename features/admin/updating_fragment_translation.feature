@managing_fragment_translations
Feature: Updating a fragment translation
  In order to change fragment translation details
  As an Administrator
  I want to be able to edit a fragment translation

  Background:
    Given the store has a fragment translation with locale "da_DK", search "search", and replace "replace"
    And I am logged in as an administrator
    And the store operates on a single channel in "United States"

  @ui
  Scenario: Updating fragment translation search string
    Given I want to update the fragment translation with locale "da_DK", search "search", and replace "replace"
    When I update the fragment translation with search "search2"
    And I save my changes
    Then I should be notified that it has been successfully edited
    And this fragment translation's search string should be "search2"