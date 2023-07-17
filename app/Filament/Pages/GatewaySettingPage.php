<?php

namespace App\Filament\Pages;

use App\Models\Config;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Contracts\HasFormActions;
use Filament\Pages\Page;
use Filament\Resources\Pages\Concerns\UsesResourceForm;
use Illuminate\Http\Request;

class GatewaySettingPage extends Page implements HasFormActions
{
    use UsesResourceForm;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.pages.gateway-setting-page';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = "Connection";

    protected static ?string $title = "Connection Setting";

    public ?string $gateway_host = "";
    public ?bool $gateway_enable = false;
    public ?string $gateway_public_keys = "";

    public ?string $smtp_host = "";
    public ?int $smtp_port = 0;
    public ?string $smtp_username = "";
    public ?string $smtp_password = "";
    public ?string $smtp_encryption = "";
    public ?string $smtp_email_from = "";
    public ?string $smtp_name_from = "";

    public ?string $sap_host = "";
    public ?string $sap_username = "";
    public ?string $sap_password = "";

    public function __construct()
    {
        parent::__construct();

        $gateway = Config::find(Config::$GATEWAY_CODE);
        if($gateway){
            $this->gateway_enable = $gateway->data['enable'];
            $this->gateway_host = $gateway->data['host'];
            $this->gateway_public_keys = $gateway->data['public_key'];
        }

        $smtp = Config::find(Config::$SMTP_CODE);
        if($smtp){
            $this->smtp_host = $smtp->data['host'];
            $this->smtp_port = (int) $smtp->data['port'];
            $this->smtp_username = $smtp->data['username'];
            $this->smtp_password = $smtp->data['password'];
            $this->smtp_encryption = $smtp->data['encryption'];
            $this->smtp_email_from = $smtp->data['email_from'];
            $this->smtp_name_from = $smtp->data['name_from'];
        }

        $sap = Config::find(Config::$SAP_CODE);
        if($sap) {
            $this->sap_host = $sap->data['host'];
            $this->sap_username = $sap->data['username'];
            $this->sap_password = $sap->data['password'];
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Heading')
            ->tabs([
                Tabs\Tab::make('Gateway Login')
                    ->schema([
                        Toggle::make('gateway_enable')
                            ->label('Enable Gateway')
                            ->inline(false),
                        TextInput::make('gateway_host')
                            ->label('Gateway Host'),
                        Textarea::make('gateway_public_keys')
                            ->label('Public Key')
                    ]),
                Tabs\Tab::make('SMTP')
                    ->schema([
                        TextInput::make('smtp_host')
                            ->label('SMTP Host'),
                        TextInput::make('smtp_port')
                            ->label('SMTP Port'),
                        TextInput::make('smtp_username')
                            ->label('SMTP Username'),
                        TextInput::make('smtp_password')
                            ->label('SMTP Password'),
                        Select::make('smtp_encryption')
                            ->label('SMTP Encryption')
                            ->options([
                                '' => 'none',
                                'TLS' => 'TLS',
                                'SSL' => 'SSL',
                            ]),
                        TextInput::make('smtp_email_from')
                            ->label('SMTP Email From'),
                        TextInput::make('smtp_name_from')
                            ->label('SMTP Name From'),

                    ]),
                Tabs\Tab::make('SAP')
                    ->schema([
                        TextInput::make('sap_host')
                            ->label('SAP Host'),
                        TextInput::make('sap_username')
                            ->label('SAP Username'),
                        TextInput::make('sap_password')
                            ->label('SAP Password'),
                    ]),
            ])

        ];
    }

    public function create(Request $request)
    {
        try {
            $gateway = [
                'enable'    => $this->gateway_enable,
                'host'      => $this->gateway_host,
                'public_key'=> $this->gateway_public_keys
            ];
            Config::updateOrCreate([
                'code'  => Config::$GATEWAY_CODE
            ],[
                'data'  => $gateway
            ]);
            $smtp = [
                'host'      => $this->smtp_host,
                'port'      => $this->smtp_port,
                'username'  => $this->smtp_username,
                'password'  => $this->smtp_password,
                'encryption'=> $this->smtp_encryption,
                'email_from'=> $this->smtp_email_from,
                'name_from'=> $this->smtp_name_from
            ];
            Config::updateOrCreate([
                'code'  => Config::$SMTP_CODE
            ],[
                'data'  => $smtp
            ]);
            $sap = [
                'host'      => $this->sap_host,
                'username'      => $this->sap_username,
                'password'  => $this->sap_password
            ];
            Config::updateOrCreate([
                'code'  => Config::$SAP_CODE
            ],[
                'data'  => $sap
            ]);

        } catch(\Exception $e){

        }

        return Notification::make()
            ->success()
            ->title('Success Save Data')
            ->body('Connection Has Been Saved')->send();
    }
}
