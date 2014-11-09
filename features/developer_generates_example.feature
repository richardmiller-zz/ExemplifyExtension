Feature: Developer generates an example
  As a Developer
  I want to automate creating examples for methods
  In order to avoid repetitive tasks and interruptions in development flow

  Scenario: Generating an example
    Given the spec file "spec/CodeGeneration/MethodExample1/MarkdownSpec.php" contains:
      """
      <?php

      namespace spec\CodeGeneration\MethodExample1;

      use PhpSpec\ObjectBehavior;
      use Prophecy\Argument;

      class MarkdownSpec extends ObjectBehavior
      {
      }

      """
    When I run phpspec exemplify to add the "toHtml" method to "CodeGeneration/MethodExample1/Markdown"
    Then the spec file "spec/CodeGeneration/MethodExample1/MarkdownSpec.php" should contain:
      """
      <?php

      namespace spec\CodeGeneration\MethodExample1;

      use PhpSpec\ObjectBehavior;
      use Prophecy\Argument;

      class MarkdownSpec extends ObjectBehavior
      {

          public function it_should_to_html()
          {
              $this->toHtml();
          }
      }

      """