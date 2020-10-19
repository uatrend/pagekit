<?php
namespace Pagekit\Application\Console;

use Pagekit\Container;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class Command extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * The console command input.
     */
    protected ?InputInterface $input = null;

    /**
     * The console command output.
     */
    protected ?OutputInterface $output = null;

    /**
     * The Container instance.
     */
    protected ?\Pagekit\Container $container = null;

    /**
     * The Pagekit config.
     */
    protected ?array $config = null;

    /**
     * Create a new console command instance.
     */
    public function __construct()
    {
        parent::__construct($this->name);
        $this->setDescription($this->description);
    }

    /**
     * Set the Pagekit application instance.
     *
     * @param Container $container
     */
    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    /**
     * Set the Pagekit config.
     *
     * @param array $config
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * Get the value of a command argument.
     *
     * @param  string $key
     * @return string|array
     */
    public function argument($key = null)
    {
        if (is_null($key)) return $this->input->getArguments();

        return $this->input->getArgument($key);
    }

    /**
     * Get the value of a command option.
     *
     * @param  string $key
     * @return string|array
     */
    public function option($key = null)
    {
        if (is_null($key)) return $this->input->getOptions();

        return $this->input->getOption($key);
    }

    /**
     * Confirm a question with the user.
     *
     * @param  string $question
     * @param  bool $default
     */
    public function confirm($question, $default = true): bool
    {
        $helper = $this->getHelperSet()->get('question');
        $question = new ConfirmationQuestion("<question>$question</question>", $default);

        return $helper->ask($this->input, $this->output, $question);
    }

    /**
     * Prompt the user for input.
     *
     * @param  string $question
     * @param  string $default
     */
    public function ask($question, $default = null): string
    {
        $helper = $this->getHelperSet()->get('question');
        $question = new Question("<question>$question</question>", $default);

        return $helper->ask($this->input, $this->output, $question);
    }

    /**
     * Prompt the user for input but hide the answer from the console.
     *
     * @param  string $question
     * @param  bool $fallback
     */
    public function secret($question, $fallback = true): string
    {
        $helper = $this->getHelperSet()->get('question');
        $question = new Question("<question>$question</question>");
        $question->setHidden(true);
        $question->setHiddenFallback($fallback);

        return $helper->ask($this->input, $this->output, $question);
    }

    /**
     * Write a string as standard output.
     *
     * @param string $string
     */
    public function line($string): void
    {
        $this->output->writeln($string);
    }

    /**
     * Write a string as information output.
     *
     * @param string $string
     */
    public function info($string): void
    {
        $this->output->writeln("<info>$string</info>");
    }

    /**
     * Write a string as comment output.
     *
     * @param string $string
     */
    public function comment($string): void
    {
        $this->output->writeln("<comment>$string</comment>");
    }

    /**
     * Write a string as question output.
     *
     * @param string $string
     */
    public function question($string): void
    {
        $this->output->writeln("<question>$string</question>");
    }

    /**
     * Write a string as error output.
     *
     * @param string $string
     */
    public function error($string): void
    {
        $this->output->writeln("<error>$string</error>");
    }

    /**
     * Aborts command execution.
     *
     * @param string $string
     */
    public function abort($string): void
    {
        $this->error($string);
        exit;
    }
}
