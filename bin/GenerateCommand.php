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
		$doc_json    = $dir.'/doc_md';
		$dirArray = scandir($doc_json);
		$tempate = '/templates/default.rt';
		
		
		foreach( $dirArray as  $file ) 
			{
				
				$pathinfo = pathinfo($file);
				
				if ( $pathinfo['extension'] == 'md' )
				{
				 
				  file_put_contents($dir.'/doc_output/'.$pathinfo['filename'].'.html', file_get_contents( $dir . $tempate ) );
					
				}
					
				
			}
		
		return "Successfully generated";
		
	}
}
