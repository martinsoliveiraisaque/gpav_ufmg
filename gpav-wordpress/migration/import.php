<?php
/** Run with: wp eval-file migration/import.php --allow-root (from package root). */
if (!defined('ABSPATH')) { fwrite(STDERR,"Run with WP-CLI eval-file after installing WordPress.\n"); exit(1); }
if (!function_exists('gpav_content_init')) WP_CLI::error('Activate gpav-content before importing.');
$payload=json_decode(file_get_contents(__DIR__.'/content.json'),true,512,JSON_THROW_ON_ERROR);
require_once ABSPATH.'wp-admin/includes/image.php';
$uploads=wp_upload_dir();$image_map=[];
$dir=$uploads['basedir'].'/gpav';
if(!is_dir($dir))WP_CLI::error('Copy wp-content/uploads/gpav before importing.');
$files=$payload['media'];
foreach($files as $source_file){
    $file=new SplFileInfo($dir.'/'.$source_file);
    if(!$file->isFile())WP_CLI::error('Missing media: '.$source_file);
    $relative='gpav/'.$source_file;
    $url=$uploads['baseurl'].'/'.$relative;
    $existing=get_posts(['post_type'=>'attachment','post_status'=>'inherit','numberposts'=>1,'meta_key'=>'_gpav_media_source','meta_value'=>$relative,'fields'=>'ids']);
    if(!$existing)$existing=get_posts(['post_type'=>'attachment','post_status'=>'inherit','numberposts'=>1,'meta_key'=>'_wp_attached_file','meta_value'=>$relative,'fields'=>'ids']);
    if($existing)$id=$existing[0];else{
        $mime=wp_check_filetype($file->getFilename())['type'];if(!$mime)continue;
        $id=wp_insert_attachment(['post_title'=>pathinfo($file->getFilename(),PATHINFO_FILENAME),'post_mime_type'=>$mime,'post_status'=>'inherit','guid'=>$url],$file->getPathname(),0,true);
        if(is_wp_error($id))WP_CLI::error($id->get_error_message());
        wp_update_attachment_metadata($id,wp_generate_attachment_metadata($id,$file->getPathname()));
    }
    update_post_meta($id,'_gpav_media_source',$relative);
    $original='https://gpav.icb.ufmg.br/wp-content/uploads/'.$relative;
    $image_map[$original]=['id'=>$id,'url'=>$url];
}
$ids=[];$records=$payload['pages_and_news'];
// Parents first: /pt/, /en/, then their children. News uses a custom post type.
usort($records,function($a,$b){return substr_count($a['route'],'/')<=>substr_count($b['route'],'/');});
foreach($records as $record){
    $route=$record['route'];$existing=get_posts(['post_type'=>$record['type'],'post_status'=>'any','numberposts'=>1,'meta_key'=>'_gpav_source_route','meta_value'=>$route,'fields'=>'ids']);
    $parent=0;
    if($record['type']==='page' && $record['kind']!=='home')$parent=$ids[$record['lang'].'/'] ?? 0;
    $date=new DateTimeImmutable($record['date']);$date=$date->setTimezone(new DateTimeZone('America/Sao_Paulo'));
    $content=str_replace('https://gpav.icb.ufmg.br/',trailingslashit(home_url()),$record['content']);
    // Preserve full HTML in native editable content rather than regenerating from Markdown.
    if($record['type']==='page')$content='<!-- wp:html -->'."\n".$content."\n".'<!-- /wp:html -->';
    $data=['post_type'=>$record['type'],'post_status'=>'publish','post_title'=>$record['title'],'post_content'=>$content,'post_name'=>$record['kind']==='home'?$record['lang']:$record['slug'],'post_parent'=>$parent,'post_author'=>0,'post_date'=>$date->format('Y-m-d H:i:s'),'post_date_gmt'=>$date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),'comment_status'=>'closed','ping_status'=>'closed'];
    if($existing)$data['ID']=$existing[0];
    $id=wp_insert_post(wp_slash($data),true);if(is_wp_error($id))WP_CLI::error($id->get_error_message());
    foreach(['lang'=>$record['lang'],'kind'=>$record['kind'],'source_route'=>$route,'imported'=>1] as $key=>$value)update_post_meta($id,'_gpav_'.$key,$value);
    if($record['cover'] && isset($image_map[$record['cover']]))set_post_thumbnail($id,$image_map[$record['cover']]['id']);
    $attachment_ids=[];
    foreach($record['attachments'] as $a){
        $path=is_array($a)?($a['file'] ?? ''):$a;
        $path=str_replace('/gpav_ufmg/assets/images/','https://gpav.icb.ufmg.br/wp-content/uploads/gpav/',$path);
        if(isset($image_map[$path]))$attachment_ids[]=$image_map[$path]['id'];
    }
    if($attachment_ids)update_post_meta($id,'_gpav_attachments',$attachment_ids);
    $ids[$route]=$id;
}
foreach($records as $record){
    if(isset($ids[$record['translation_route']]))update_post_meta($ids[$record['route']],'_gpav_translation',$ids[$record['translation_route']]);
}
foreach($payload['team'] as $member){
    $existing=get_posts(['post_type'=>'gpav_member','post_status'=>'any','numberposts'=>1,'meta_key'=>'_gpav_source_name','meta_value'=>$member['name'],'fields'=>'ids']);
    $data=['post_type'=>'gpav_member','post_status'=>'publish','post_title'=>$member['name'],'post_author'=>0,'comment_status'=>'closed','ping_status'=>'closed'];if($existing)$data['ID']=$existing[0];
    $id=wp_insert_post(wp_slash($data),true);if(is_wp_error($id))WP_CLI::error($id->get_error_message());
    update_post_meta($id,'_gpav_source_name',$member['name']);
    foreach(['role','title','bio_pt','bio_en','order','lattes'] as $key)update_post_meta($id,'_gpav_'.$key,$member[$key] ?? ($key==='order'?1:''));
    if(isset($image_map[$member['photo']]))set_post_thumbnail($id,$image_map[$member['photo']]['id']);
}
update_option('blogname','GPAV UFMG');update_option('blogdescription','Grupo de Pesquisa em Arboviroses · ICB · UFMG');
update_option('timezone_string','America/Sao_Paulo');update_option('WPLANG','pt_BR');
update_option('permalink_structure','/%postname%/');update_option('show_on_front','posts');
update_option('default_comment_status','closed');update_option('default_ping_status','closed');
update_option('gpav_options',['formspree_url'=>'https://formspree.io/f/mqeeekln','pubmed_email'=>'secchimribeiro@gmail.com','pubmed_tool'=>'gpav_ufmg_website','pubmed_term'=>gpav_default_pubmed_term()]);
flush_rewrite_rules();
WP_CLI::success(count($records).' pages/news, '.count($payload['team']).' team members and '.count($image_map).' media files imported.');
