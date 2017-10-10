<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    protected $commandName = 'doc:make';
    protected $commandDescription = "Generate documents";

    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "Who do you want to greet?";

     protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
	      $output->writeln($this->createHtmlOutPut());
    }


    private function createHtmlOutPut()
    {

    $dir = str_replace('/bin', '', __DIR__);

		foreach( $this->scanDir() as  $file )
			{

				$pathinfo = pathinfo($file);

				if ( $pathinfo['extension'] == 'md' )
				{

				  file_put_contents($dir.'/doc_output/'.$pathinfo['filename'].'.html', $this->processMdFile($dir,  $file) );

				}

			}

		return "Successfully generated";

	}

  private function scanDir()
  {
    $dir = str_replace('/bin', '', __DIR__);
    $doc_json = $dir.'/doc_md';
    return scandir($doc_json, 1);
  }

  private function createNav()
  {

    $nav = '';

    foreach( $this->scanDir() as  $file )
      {

        $pathinfo = pathinfo($file);

        if ( $pathinfo['extension'] == 'md' )

            $nav .= '<li><a href="'.$pathinfo['filename'].'.html" class="toc-h1 toc-link">'.$pathinfo['filename'].'</a></li>';

      }

      return $nav;

  }


  private function processMdFile( $dir,  $file )
  {
		$tempate = '/templates/default.rt';
    $template = file_get_contents( $dir . $tempate );

    $mdFileContent = file_get_contents( $dir . '/doc_md/' . $file );

    foreach( TagsGenerator::tagsDefinations() as $tag => $htmlTag )
      $mdFileContent =   str_replace( $tag, $htmlTag, $mdFileContent);


      $template = str_replace( '<-nav->', $this->createNav(), $template);

      return str_replace( '<-content->', $mdFileContent, $template);

  }


}
