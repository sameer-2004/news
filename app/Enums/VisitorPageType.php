<?php
 namespace App\Enums;

 interface VisitorPageType
 {
 	const HomePage          	= 1;
 	const PostDetailPage    	= 2;
 	const PageDetailPage    	= 3;
 	const PostByTagsPage    	= 4;
 	const PostByCategoryPage  	= 5;
 	const PostBySubCategoryPage = 6;
 	const PostByAuthorPage 		= 7;
 	const PostByDatePage 		= 8;
 }
