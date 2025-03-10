<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use Carbon\CarbonImmutable;
use Carbon\Exceptions\EndLessPeriodException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('blogs.index', ['blogs' => Auth::user()->blogs]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $tags = Tag::select(["id", "title"])->get();
        $category = Category::select(["id", "title"])->get();


        return view('blogs.create', ["tags" => $tags, "categories" => $category]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:3',
            'tag_id' => 'nullable|array',
            'category_id' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'additional_image' => 'nullable|array',
        ]);
        $tags = $validated["tag_id"] ?? null;


        $folderName = 'images/' . $request["title"] . "-" .  date("Y-m-d");


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('storage/' . $folderName), $filename);
            $pathImage = $folderName . '/' . $filename;

            // $pathImage = $request->file('image')->store($folderName, 'public');
        } else {
            $pathImage = null;
        }

        if ($request->hasFile('additional_image')) {
            $additional = [];
            $files = $request->file('additional_image');
            foreach ($files as $file) {
                // Generate a unique filename for each file
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '-' . uniqid() . '.' . $extension;
                // Store the file and save the path
                $filePath = $file->storeAs($folderName . "/blog-images", $filename, 'public');
                $additional[] = $filePath;
            }
        } else {
            $additional = [];
        }

        // dd($pathImage, $additional);


        $blog =  DB::table("blogs")->insertGetId([
            'user_id' => Auth::user()->id,
            'title' => $validated["title"],
            'description' => $validated["description"],
            'category_id' => $validated["category_id"],
            'image' => $pathImage,
            'additional_images' => json_encode($additional),
        ]);

        $blogId = Blog::find($blog);

        $blogId->tags()->sync($tags);
        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)

    {
        $comments = DB::select("select u.name,c.* from comments c join users u on c.user_id = u.id where c.blog_id = '$blog->id'");

        return view('blogs.show', ['blog' => $blog, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        $tags = DB::select("select t.title, t.id from tags t left join blog_tag bt on bt.tag_id = t.id where bt.blog_id = ?", [$blog->id]);

        $tagIds = collect($tags)->pluck('id');

        $remainingTags = DB::table('tags')
            ->select(['id', 'title'])
            ->whereNotIn('id', $tagIds)
            ->get();


        $category = DB::select("select c.title,c.id from categories c where c.id = '$blog->category_id'");


        $cat_id = collect($category)->pluck('id');
        $remainingCat = DB::table('categories')->select(['id', 'title'])->whereNotIn('id', $cat_id)->get();

        return view('blogs.edit', ['blog' => $blog, 'sel_tags' => $tags ?? [], "other_tags" => $remainingTags ?? [], "sel_cat" => $category ?? [], "other_cat" => $remainingCat ?? []]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($comment)
    {
        DB::table('comments')
            ->where('id', $comment)
            ->update(['status' => DB::raw("IF(status = 1, 0, 1)")]);
        return redirect()->back();
    }
    public function updates(Request $request, Blog $blog)
    {

        $validated = $request->validate([
            'title' => 'required',
            'tag_id' => 'required | array',
            'category_id' => 'required',
            'description' => 'required'
        ]);

        $tags = $validated["tag_id"];

        $table = DB::table('blog_tag')
            ->join('tags', 'blog_tag.tag_id', '=', 'tags.id')
            ->where('blog_tag.blog_id', $blog->id)
            ->get();
        $blog->tags()->sync($tags);

        // dd($tags,$table);        

        $blog->update([
            'title' => $validated["title"],
            'description' => $validated["description"],
            'category_id' => $validated["category_id"],
        ]);
        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully.');
    }
    public function one_blog($blogId)
    {
        $blog = Blog::where('blogs.id', $blogId)
            ->leftJoin("users", "users.id", "=", "blogs.user_id")
            ->select("blogs.*", "users.name")
            ->firstOrFail(); // Fetch single blog post or fail if not found


        if (!empty($blog)) {

            if ($blog->image && !str_starts_with($blog->image, 'http')) {
                $blog->image = url('storage/' . $blog->image);
            }
            $additional = $blog->additional_images;

            if (!empty($blog->additional_images)) {
                // Decode the JSON string into an array

                $images = [];
                foreach ($additional as $image) {
                    // Append full URL for non-HTTP image paths
                    if (!str_starts_with($image, 'http')) {
                        $fullUrl = Storage::url($image);
                        $images[] = url($fullUrl);
                    } else {
                        // Keep the image URL as is if it already starts with http
                        $images[] = $image;
                    }
                }
                // Add the images to the blog object
                $blog->additional_images = $images;
            }
            if (strpos($blog->description, "{0}") !== false) {
                for ($i = 0; $i < count($blog->additional_images); $i++) {
                    $blog->description = str_replace(
                        "{{$i}}",
                        "<div class='imageRemote'><img src='{$blog->additional_images[$i]}'/></div>",
                        $blog->description
                    );
                }
            }

            // Return the response with the blog data
            return response()->json([
                "status" => 200,
                "success" => "true",
                "data" => $blog,
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "success" => "false",
            ]);
        }
    }

    public function all_blogs_front()
    {
        $blogsJoin = Blog::select("title")->limit(6)->get();

        if ($blogsJoin->isNotEmpty()) {
            return response()->json([
                "status" => 200,
                "success" => "true",
                "data" => $blogsJoin,
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "success" => "false",
            ]);
        }
    }
    public function all_blogs_common($blogsJoin)
    {
        $blogsJoin->getCollection()->transform(function ($blog) {
            if ($blog->image && !str_starts_with($blog->image, 'http')) {
                $blog->image = url('storage/' . $blog->image);
            }
            if ($blog->created_at) {
                $blog->created_at = $blog->created_at->format('F j, Y');
            }
            return $blog;
        });
    }
    public function all_blogs_search(Request $request)
    {
        $query = Blog::query();

        if ($request->has('search')) {
            $blogs = $query->where('description', 'LIKE', '%' . $request->input('search') . '%');

            $blogsJoin = $blogs->paginate(6);

            if ($blogsJoin->isNotEmpty()) {
                $this->all_blogs_common($blogsJoin);

                return response()->json([
                    "status" => 200,
                    "success" => "true",
                    "data" => $blogsJoin,
                ]);
            } else {
                return response()->json([
                    "status" => 404,
                    "success" => "false",
                ]);
            }
        } else {
            return $this->all_blogs();
        }
    }
    public function all_blogs()
    {
        $blogsJoin = Blog::paginate(6);

        if ($blogsJoin->isNotEmpty()) {

            $this->all_blogs_common($blogsJoin);
            return response()->json([
                "status" => 200,
                "success" => "true",
                "data" => $blogsJoin,
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "success" => "false",
            ]);
        }
    }

    public function blogs_tags($blogId)
    {
        $selected = DB::select("
        SELECT t.title, t.id 
        FROM tags t 
        JOIN blog_tag bt ON bt.tag_id = t.id 
        WHERE bt.blog_id = ?", [$blogId]);

        // For unselected, we select all tags that are NOT associated with the given blog
        $unselected = DB::select("
        SELECT t.title, t.id 
        FROM tags t 
        WHERE t.id NOT IN (
            SELECT bt.tag_id 
            FROM blog_tag bt 
            WHERE bt.blog_id = ?
        )", [$blogId]);

        if (!empty($selected)) {
            return response()->json([
                "status" => 200,
                "success" => "true",
                "data" => [
                    'selected' => $selected,
                    'unselected' => $unselected,
                ]
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "success" => "false",
            ]);
        }
    }
    public function recent_blogs()
    {
        $blogsJoin = DB::table('blogs')
            ->where('created_at', '<=', now())
            ->orWhere('updated_at', '<=', now())
            ->limit(6)
            ->get();
        if ($blogsJoin->isNotEmpty()) {
            return response()->json([
                "status" => 200,
                "success" => "true",
                "data" => $blogsJoin
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "success" => "false",
            ]);
        }
    }
    public function blogs_with_cat()
    {
        $category = Category::with("blog:id,title,description,category_id")->get(['id', 'title']);

        $categoriesWithBlogs = $category->sortByDesc(function ($category) {
            return $category->blog->isNotEmpty() ? 1  : 0;
        });

        if ($categoriesWithBlogs->isNotEmpty()) {
            return response()->json([
                "status" => 200,
                "success" => "true",
                "data" => $categoriesWithBlogs
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "success" => "false",
            ]);
        }
    }
    public function blogs_with_catid($categoryId)
    {

        $blogsJoin = DB::table('blogs as b')
            ->Join("categories as c", 'b.category_id', "=", "c.id")->where("c.id", "=", $categoryId)->get();
        if ($blogsJoin->isNotEmpty()) {
            return response()->json([
                "status" => 200,
                "success" => "true",
                "data" => $blogsJoin
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "success" => "false",
            ]);
        }
    }

    public function postComment(Request $request, $blogId)
    {
        $title = $request->input('title', '');
        $message = $request->input('message', '');
        $user_id = $request->input('user_id', '');

        $userExists = DB::select("select id from users where id = ?", [$user_id]);
        if (!empty($userExists)) {
            DB::insert("insert into comments (title, message, blog_id, user_id, status) values (?, ?, ?, ?, ?)", [$title, $message, $blogId, $user_id, '0']);
            return response()->json([
                "status" => 200,
                "success" => "true",
                "data" => "Message posted successfully",
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "success" => "false",
                "data" => "you are not authorized to post this comment",
            ]);
        }
    }

    public function getComments($blogId)
    {
        $comments = Comment::select("comments.*", "users.name")
            ->join("users", "users.id", "=", "comments.user_id")
            ->where('blog_id', '=', $blogId)
            ->get();

        if ($comments->isNotEmpty()) {
            return response()->json([
                "status" => 200,
                "success" => "true",
                "data" => $comments,
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "success" => "false",
                "data" => "No comments available",
            ]);
        }
    }
}
