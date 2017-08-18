
<?php
  class Sahadan {

    private $data;
    private $events    = [];
    private $baseUrl   = 'http://www.sahadan.com/';
    private $eventsUrl = 'http://www.sahadan.com/AjaxHandlers/IddaaHandler.aspx?command=tab&type=2&st=Football&l=-1&d=-1&i=0&t=&ip=1&w=&g=7&np=1&srt=-1&srtd=1';
    private $referrer  = 'http://www.sahadan.com/Iddaa/program.aspx';
    private $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2431.0 Safari/537.36';

    /**
    * Sahadan Constructor
    */
    public function __construct()
    {
      $this->connect();
    }

    /**
    * @param string $url
    * @return mixed
    */
    private function connect($url = null)
    {
      $ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_URL, $url ? $url : $this->eventsUrl);
			curl_setopt($ch, CURLOPT_REFERER, $this->referrer);
			curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			$this->data = curl_exec($ch);
			curl_close($ch);
    }

    /**
    * @return mixed
    */
    public function events()
    {
      preg_match_all('#<tr class="(.*?)"(.*?)</tr>#Ssie', $this->data, $tr);
      $response = [];
      $nowDate  = null;

      for ($i=0; $i < count($tr[1]); $i++)
      {
        if ($tr[1][$i] == 'iddaa-oyna-title2')
        {
          // Eğer ki gelen tarih ise diziye atayacağız.
          preg_match_all('#rateSort="tarih_1">(.*?)</td>#Ssie', $tr[2][$i], $event_date);
          $response[$event_date[1][0]] = [];
          $nowDate = $event_date[1][0];
        }
        else
        {
          preg_match_all('#<td width="37" align="center"><b>(.*?)</b></td>#Ssie', $tr[2][$i], $parseCode);
          preg_match_all('#<td width="45" align="center">(.*?)</td>#Ssie', $tr[2][$i], $parseTime);
          preg_match_all('#<td width="25" style="cursor:pointer" onclick="PDc\((.*?),(.*?),\'(.*?)\'\)"><img src="(.*?)" style="vertical-align: middle" /></td>#Ssie', $tr[2][$i], $parseCountryLogo);
          preg_match_all('#<td width="35" style="cursor:pointer" onclick="PDc\((.*?),(.*?),\'(.*?)\'\)">(.*?)</td>#Ssie', $tr[2][$i], $parseCountry);
          preg_match_all('#<td width="17" class="mbs-(.*?)"><img src="http://is.cdn.md/i4/New/img/iddaa/mbs(.*?).png"></td>#Ssie', $tr[2][$i], $parseMbs);
          preg_match_all('#<td><a href="javascript:MoreBets\((.*?),#Ssie', $tr[2][$i], $parseEventId);
          preg_match_all('#<td><a class=\'iddaa-rows-style\' href=\'javascript:Tc\((.*?), (.*?), "(.*?)"\)\'>(.*?)<span class=\'cc-hand\'>(.*?)</span></a>  <a class=\'iddaa-rows-style\' href=\'javascript:popMatch\((.*?),"ByDate"\)\'> - </a>  <a class=\'iddaa-rows-style\' href=\'javascript:Tc\((.*?), (.*?), "(.*?)"\)\'>(.*?)<span class=\'cc-hand\'>(.*?)</span></a></td>#Ssie', $tr[2][$i], $parseTeamName);

          $response[$nowDate][] = [
            'id'       => trim($parseEventId[1][0]),
            'code'     => trim($parseCode[1][0]),
            'time'     => trim($parseTime[1][0]),
            'mbs'      => trim($parseMbs[2][0]),
            'country'  => [
              'id'     => trim($parseCountryLogo[2][0]),
              'name'   => trim($parseCountry[4][0]),
              'flag'   => trim($parseCountryLogo[4][0])
            ],
            'league'   => [
              'id'     => trim($parseCountryLogo[1][0]),
              'name'   => trim($parseCountryLogo[3][0])
            ],
            'team'     => [
              'home'   => [
                'id'   => 0,
                'name' => trim($parseTeamName[4][0]),
                'hand' => trim($parseTeamName[5][0])
              ],
              'away'   => [
                'id'   => 0,
                'name' => trim($parseTeamName[10][0]),
                'hand' => trim($parseTeamName[11][0])
              ]
            ]
          ];
        }
      }

      return $response;
    }

    /**
    * @param int $eventId
    * @param string $eventCode
    * @return mixed
    */
    public function eventDetail($eventId, $eventCode)
    {
      //
    }

  }
?>
