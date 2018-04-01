<?php

namespace App\Console\Commands;

use App\Mattermost\Token;
use App\Team;
use Illuminate\Console\Command;

class MattermostConfigureTeam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'team:config {team_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configures Mattermost team. Adds routes and security tokens.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Adds a token for the team.
     *
     * @param Team $team
     * @param Token $token
     * @param string $command
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function addToken(Team $team, Token $token, $command)
    {
        return $team->tokens()->create([
            'id' => $token,
            'command' => $command,
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('team_name');
        $team = Team::where('mm_domain', $name)->first();

        if (!$team)
        {
            $this->error("Team $name does not exist.");
            return;
        }

        $this->info('You will be asked to input values that Mattermost marks as \'command\' in request.');
        $this->info('E.g. /team . You must write the heading slash.');


        do
        {
            $command = $this->ask('Enter command');
            $token = $this->ask('Enter token');

            $team->tokens()->create(['command' => $command, 'id' => $token]);
        } while($this->confirm('Enter one more token-command pair?'));
        return;
    }
}
