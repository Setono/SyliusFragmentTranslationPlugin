@managing_fragment_translations
Feature: Adding a new fragment translation
  In order to easily translate fragments on my website
  As an Administrator
  I want to add a new fragment translation

  Background:
    Given I am logged in as an administrator
    And the store operates on a single channel in "United States"
    And the store has locale "da_DK"

  @ui
  Scenario: Adding a new fragment translation
    Given I want to create a new fragment translation
    When I fill the locale with "da_DK"
    And I fill the search string with "search"
    And I fill the replacement with "replacement"
    And I add it
    Then I should be notified that it has been successfully created
    And the fragment translation with locale "da_DK", search "search", and replacement "replacement" should appear in the store
