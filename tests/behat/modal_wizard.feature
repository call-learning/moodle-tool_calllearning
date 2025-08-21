@tool_calllearning
Feature: Open modal wizard fixture page
  In order to verify access to the modal wizard fixture page

  Scenario: Open modal_wizard fixture page
    Given I am on fixture page "/admin/tool/calllearning/tests/behat/fixtures/modal_wizard.php"
    And I click on "Open modal wizard" "link"
    Then I should see "Modal wizard fixture page" in the ".modal-title" "css_element"
    And I should see "This is a modal wizard fixture page." in the ".modal-body" "css_element"
    And I should see "Close" "button" in the ".modal-footer" "css_element"
    And I should see "Next" "button" in the ".modal-footer" "css_element"
    And I should see "Previous" "button" in the ".modal-footer" "css_element"
    And I should see "Finish" "button" in the ".modal-footer" "css_element"
    And I should see "Cancel" "button" in the ".modal-footer" "css_element"
    And I should see "Step 1 of 3" in the ".modal-body" "css_element"
    Then I click on "Next" "button" in the ".modal-footer" "css_element"
    And I should see "Step 2 of 3" in the ".modal-body"
